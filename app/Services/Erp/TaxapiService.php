<?php

namespace App\Services\Erp;

use App\Repositories\InvoiceRepository;
use App\Repositories\OrderRepository;
use App\Repositories\FilingRepository;
use App\Services\Zjport\ZjportService;
use DB;

class TaxapiService
{
    public function __construct(InvoiceRepository $invoiceRepository, OrderRepository $orderRepository, FilingRepository $filingRepository, ZjportService $zjportService)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->orderRepository = $orderRepository;
        $this->filingRepository = $filingRepository;
        $this->zjportService = $zjportService;
    }

    public function uninvoiceOrderProListService($param)
    {
        $list = $this->invoiceRepository->uninvoiceOrderProList($param);
        $data = array_map(function($value){
            $item = [];
            $item['drawer_product_order_id'] = $value['id'];
            $item['company'] = $value['company'] ? : $value['drawer_product']['drawer']['company'];
            $item['tax_id'] = $value['drawer_product']['drawer']['tax_id'];
            $item['drawer_id'] = $value['drawer_product']['drawer']['id'];
            $item['order_id'] = $value['order']['id'];
            $item['ordnumber'] = $value['order']['ordnumber'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function orderWithProDetailService($param)
    {
        $drawer_id = $param['drawer_id'];
        $order_id = $param['order_id'];
        $order = $this->orderRepository->find($order_id);

        $drawer_products = $order->drawerProducts;
        $drawer_product_order = [];
        $order->drawerProducts->each(function($value, $key)use(&$drawer_product_order, $drawer_id){
            //该开票工厂的产品并且未全部开完票的产品
            if($value->drawer_id == $drawer_id && $value->pivot->number > $value->pivot->invoice_quantity){
                $item = [];
                $item['drawer_product_order_id'] = $value->pivot->id;
                $item['serial_no'] = $key+1;
                $item['drawer_id'] = $value->drawer_id;
                $item['product_id'] = $value->product_id;
                $item['hscode'] = $value->product->hscode;
                $item['name'] = $value->product->name;
                $item['tax_refund_rate'] = $value->pivot->tax_refund_rate;
                $item['unit'] = $value->pivot->measure_unit_cn;
                $item['quantity'] = $value->pivot->number;
                $item['value'] = $value->pivot->total_price;
                $item['un_invoice_quantity'] = bcsub($value->pivot->number, $value->pivot->invoice_quantity, 2);
                $item['invoice_sum_amount'] = $value->pivot->invoice_amount;
                $item['default_unit_id'] = $value->pivot->default_unit_id;
                $item['default_unit'] = $value->pivot->default_unit;
                $item['default_num'] = $value->pivot->default_num;
                $item['merge'] = $value->pivot->merge;
                $drawer_product_order[] = $item;
            }
        });
        $order_data = [];
        $order_data['id'] = $order->id;
        $order_data['order_invoice_amount'] = $order->total_value_invoice;
        $order_data['export_date'] = $order->export_date;
        $order_data['customs_number'] = $order->customs_number;
        $order_data['drawer_product_order'] = $drawer_product_order;
        return $order_data;
    }

    public function invoiceCompleteService($invoice, $product)
    {
        DB::beginTransaction();
        $order = $this->orderRepository->getOneOrderByWhere(['id'=> $invoice['order_id']]);

        $tax_invoice = [];
        if($invoice['tax_type'] == 0){
            $tax_invoice['cid'] = 0;
        }else{
            $tax_invoice['cid'] = $invoice['company_id'];
        }
        $tax_invoice['erp_id'] = $invoice['id'];
        $tax_invoice['order_id'] = $invoice['order_id'];
        $tax_invoice['drawer_id'] = $invoice['drawer_id'];
        $tax_invoice['number'] = $invoice['invoice_number'];
        $tax_invoice['billed_at'] = $invoice['billed_at'];
        $tax_invoice['received_at'] = $invoice['received_at'];
        $tax_invoice['invoice_amount'] = $invoice['invoice_amount'];
        $tax_invoice['status'] = 3;
        $tax_invoice['approved_at'] = date('Y-m-d');
        $tax_invoice['created_user_id'] = $order->created_user_id;
        $tax_invoice['created_user_name'] = $order->created_user_name;

        $invoice_id = $this->invoiceRepository->save($tax_invoice);
        if(!$invoice_id){
            DB::rollback();
            return ['status'=>0, 'msg'=> '添加发票失败'];
        }

        $this->invoiceRepository->relateDrawerProductOrder($invoice_id, $product);

        //判断订单是否已全部开完票
        $invoice_complete = 1; //已全部开完
        $order->drawerProducts->each(function($item, $key)use(&$invoice_complete){
            if($item->pivot->number > $item->pivot->invoice_quantity){
                $invoice_complete = 0;
            }
        });
        if($invoice_complete == 1){
            $order_data['invoice_complete'] = 1;
        }
        $order_data['export_date'] = $invoice['export_date'];
        $order_res = $order->update($order_data);

        if($invoice['tax_type'] == 1){ //外综
            $purinvoice_res = $this->zjportService->purinvoice($invoice_id);
        }
        DB::commit();
        return ['status'=>1, 'msg'=> '添加发票成功'];
    }

    public function filingStartService($id, $invoice)
    {
        DB::beginTransaction();
        $invoice_ids = array_reduce($invoice, function($ret, $value){
            array_push($ret, $value['id']);
            return $ret;
        },[]);
        $invoice_res = $this->invoiceRepository->updateInvoiceByErpid($invoice_ids, ['status'=> 5]);
        //设置哪些订单已申报
        $invoices = $this->invoiceRepository->selectByErpid($invoice_ids);
        $order_ids = array_reduce($invoices, function($ret, $value){
            array_push($ret, $value['order_id']);
            return $ret;
        },[]);
        
        $order_res = $this->orderRepository->updateByWhereIn(['invoice_complete' => 2], $order_ids);
        if($invoice_res && $order_res){
            DB::commit();
            return ['status'=>1, 'msg'=> '添加申报成功'];
        }else{
            DB::rollback();
            return ['status'=>0, 'msg'=> '关联发票失败'];
        }
    }

    public function filingCompleteService($filing, $invoice)
    {
        DB::beginTransaction();
        $invoice_ids = array_reduce($invoice, function($ret, $value){
            array_push($ret, $value['id']);
            return $ret;
        },[]);
        $invoices = $this->invoiceRepository->selectByErpid($invoice_ids);
        $order_ids = array_reduce($invoices, function($ret, $value){
            array_push($ret, $value['order_id']);
            return $ret;
        },[]);
        $order = $this->orderRepository->getOneOrderByWhere(['id'=> $order_ids[0]]);

        $tax_filing = [];
        if($filing['tax_type'] == 0){
            $tax_filing['cid'] = 0;
        }else{
            $tax_filing['cid'] = $filing['company_id'];
        }
        $tax_filing['erp_id'] = $filing['id'];
        $tax_filing['batch'] = $filing['batch'];
        $tax_filing['applied_at'] = $filing['applied_at'];
        $tax_filing['declared_amount'] = $filing['declared_amount'];
        $tax_filing['amount'] = $filing['amount'];
        $tax_filing['invoice_quantity'] = $filing['invoice_quantity'];
        $tax_filing['returned_at'] = $filing['returned_at'];
        $tax_filing['letter'] = isset($filing['letter']) ? $filing['letter'] : '';
        $tax_filing['status'] = 2;
        $tax_filing['approved_at'] = date('Y-m-d');
        $tax_filing['entry_person'] = $filing['created_username'];
        $tax_filing['created_user_id'] = $order->created_user_id;
        $tax_filing['created_user_name'] = $order->created_user_name;

        $filing_id = $this->filingRepository->save($tax_filing);
        if(!$filing_id){
            DB::rollback();
            return ['status'=>0, 'msg'=> '添加申报失败'];
        }

        $invoice_res = $this->invoiceRepository->updateInvoiceByErpid($invoice_ids, ['filing_id'=> $filing_id, 'status'=> 6]);

        //设置哪些订单已退税
        $order_res = $this->orderRepository->updateByWhereIn(['invoice_complete' => 3], $order_ids);
        if($invoice_res && $order_res){
            DB::commit();
            return ['status'=>1, 'msg'=> '添加申报成功'];
        }else{
            DB::rollback();
            return ['status'=>0, 'msg'=> '关联发票失败'];
        }
    }
}

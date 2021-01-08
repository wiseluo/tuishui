<?php

namespace App\Services\Member;

use App\Repositories\InvoiceRepository;

class InvoiceService
{
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function invoiceIndex($param)
    {
        $list = $this->invoiceRepository->invoiceIndex($param);
        //dd($list);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['ordnumber'] = $value['order']['ordnumber'];
            $item['drawer_company'] = $value['drawer']['company'];
            $item['number'] = $value['number'];
            $item['billed_at'] = $value['billed_at'];
            $item['invoice_amount'] = $value['invoice_amount'];
            //应退税款
            $item['refund_tax_amount_sum'] = array_reduce($value['drawer_product_orders'], function($carry, $v){
                return bcadd($carry, $v['pivot']['refund_tax_amount'], 2);
            }, 0);
            //已退税款
            $item['refunded_tax_amount_sum'] = $value['status'] == 6 ? $item['refund_tax_amount_sum'] : 0;
            $item['received_at'] = $value['received_at'];
            $item['status'] = $value['status'];
            $item['status_str'] = $value['status_str'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function find($id)
    {
        return $this->invoiceRepository->find($id);
    }

    public function orderListService($param)
    {
        $list = $this->invoiceRepository->orderList($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['ordnumber'] = $value['ordnumber'];
            $item['status'] = $value['status'];
            $item['customer_name'] = $value['customer']['name'];
            $item['clearance_status'] = $value['clearance_status'];
            $item['company_name'] = $value['company']['name'];
            $item['total_value_invoice'] = $value['total_value_invoice'];
            $item['tdnumber'] = $value['tdnumber'];
            $item['created_at'] = $value['created_at'];
            $item['invoice_complete_str'] = $value['invoice_complete_str'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function orderInvoiceListService($id)
    {
        $list = $this->invoiceRepository->orderInvoiceList($id);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['ordnumber'] = $value['order']['ordnumber'];
            $item['drawer_company'] = $value['drawer']['company'];
            $item['number'] = $value['number'];
            $item['billed_at'] = $value['billed_at'];
            $item['invoice_amount'] = $value['invoice_amount'];
            //应退税款
            $item['refund_tax_amount_sum'] = array_reduce($value['drawer_product_orders'], function($carry, $v){
                return bcadd($carry, $v['pivot']['refund_tax_amount'], 2);
            }, 0);
            //已退税款
            $item['refunded_tax_amount_sum'] = $value['status'] == 6 ? $item['refund_tax_amount_sum'] : 0;
            $item['received_at'] = $value['received_at'];
            $item['status'] = $value['status'];
            $item['status_str'] = $value['status_str'];
            return $item;
        }, $list);
        return $data;
    }
}

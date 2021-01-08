<?php

namespace App\Repositories;

use App\Invoice;
use App\Order;
use App\DrawerProductOrder;

class InvoiceRepository
{
    public function find($id)
    {
        return Invoice::find($id);
    }

    public function invoiceIndex($param)
    {
        $where = [];
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        if($param['status']){
            $where[] = ['status', '=', $param['status']];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Invoice::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->whereHas('order', function ($query) use ($keyword){
                        $query->where('ordnumber', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('drawer', function ($query) use ($keyword){
                        $query->where('company', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhere('number', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->where($where)
            ->where($userlimit)
            ->with(['order','drawer','drawerProductOrders'])
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    //获取未开完发票的订单开票产品列表
    public function uninvoiceOrderProList($param)
    {
        $company_id = $param['company_id'];
        if($param['tax_type'] == 0){
            $cid = 0;
        }else{
            $cid = $company_id;
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        return DrawerProductOrder::whereHas('order', function($query)use($cid, $company_id){
            $query->where([['status', '=', 5], ['invoice_complete', '=', 0], ['cid', '=', $cid], ['company_id', '=', $company_id]]); //已结案，未开票完成
        })
        ->whereColumn('number', '>', 'invoice_quantity') //产品数量>累计已开票产品数量
        ->where(function($query)use($keyword){
            $query->when($keyword, function($query)use($keyword){
                return $query->whereHas('order', function($query) use($keyword){
                    $query->where('ordnumber', 'LIKE', '%'. $keyword  .'%');
                })
                ->orWhere('company', 'LIKE', '%'. $keyword .'%');
            });
        })
        ->with(['order', 'drawerProduct.drawer'])
        ->orderBy('id', 'desc')
        //->toSql();
        ->paginate(10)
        ->toArray();
    }

    public function relateDrawerProductOrder($id, $product)
    {
        $invoice = Invoice::find($id);
        array_map(function($value)use($invoice){
            $item = [];
            $item['tax_rate'] = $value['product_tax_rate'];
            $item['single_price'] = $value['product_single_price'];
            $item['quantity'] = $value['product_invoice_quantity'];
            $item['amount'] = $value['product_invoice_amount'];
            $item['refund_tax_amount'] = $value['product_refund_tax_amount'];
            $item['product_untaxed_amount'] = $value['product_untaxed_amount'];
            $item['product_tax_amount'] = $value['product_tax_amount'];
            $item['default_unit_id'] = $value['product_default_unit_id'];
            $item['default_unit'] = $value['product_default_unit'];
            $item['default_quantity'] = $value['product_default_quantity'];
            $invoice->drawerProductOrders()->attach([$value['drawer_product_order_id'] => $item]);
            //累加已开票数量,发票金额到订单产品关系表中
            $drawerProductOrders = DrawerProductOrder::find($value['drawer_product_order_id']);
            $invoice_quantity = bcadd($value['product_invoice_quantity'], $drawerProductOrders->invoice_quantity, 2);
            $invoice_amount = bcadd($value['product_invoice_amount'], $drawerProductOrders->invoice_amount, 2);
            $drawerProductOrders->update(['invoice_quantity'=> $invoice_quantity, 'invoice_amount'=> $invoice_amount]);
        }, $product);
        return 1;
    }

    public function orderList($param)
    {
        $where = [];
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        $where[] = ['status', '=', 5];
        // dd($where);
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Order::where(function($query)use($keyword){
            $query->when($keyword, function($query)use($keyword){
                return $query->whereHas('drawerProducts.drawer', function ($query) use ($keyword){
                        $query->where('company', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('customer', function ($query) use ($keyword){
                        $query->where('name', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('salesman', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('created_user_name', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhere('ordnumber', 'LIKE', '%'. $keyword .'%')
                    ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
            });
        })
        ->with(['customer', 'company'])
        ->where($where)
        ->where($userlimit)
        ->orderBy('id', 'desc')
        //->toSql();
        ->paginate($pageSize)
        ->toArray();
    }

    public function orderInvoiceList($id)
    {
        $where[] = ['order_id', '=', $id];
        return Invoice::where($where)
            ->with(['order','drawer','drawerProductOrders'])
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }
    
    public function save($param)
    {
        $invoice = new Invoice($param);
        $invoice->save();
        return $invoice->id;
    }

    public function updateInvoiceByErpid($erp_ids, $param)
    {
        return Invoice::whereIn('erp_id', $erp_ids)->update($param);
    }

    public function selectByErpid($erp_ids)
    {
        return Invoice::whereIn('erp_id', $erp_ids)->get()->toArray();
    }

    public function getInvoiceExportData($param)
    {
        $where = [];
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        if(isset($param['status'])){
            $where[] = ['status', '=', $param['status']];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        return Invoice::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->whereHas('order', function ($query) use ($keyword){
                        $query->where('ordnumber', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('drawer', function ($query) use ($keyword){
                        $query->where('company', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhere('number', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->where($where)
            ->where($userlimit)
            ->get();
    }
}
<?php

namespace App\Repositories\Api;

use App\Invoice;

class InvoiceRepository
{
    public function invoiceIndex($param)
    {
        //找到这个客户在这个cid公司下的状态为status的发票
        $where = [];
        $where[] = ['cid', '=', $param['cid']];
        $where[] = ['status', '=', $param['status']];
        $customer_id = $param['customer_id'];
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
                    });
                });
            })
            ->whereHas('order', function($query)use($customer_id){
                $query->where('customer_id', $customer_id);
            })
            ->where($where)
            ->with(['order','drawer','drawerProductOrders'])
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    public function find($id)
    {
        return Invoice::find($id);
    }

    public function findWithRelation($id)
    {
        return Invoice::with(['order','drawer','drawerProductOrders.drawerProduct.product'])->find($id);
    }

}
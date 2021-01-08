<?php

namespace App\Repositories;

use App\DrawerProductOrder;

class ReportRepository
{
    public function reportIndex($param)
    {
        $where = [];
        if($param['cid'] !== ''){
            $where[] = ['cid', '=', $param['cid']];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        $apply_start_date = isset($param['apply_start_date']) ? $param['apply_start_date'] : '1970-01-01';
        $apply_end_date = isset($param['apply_end_date']) ? $param['apply_end_date'] : date('Y-m-d');
        return DrawerProductOrder::with(['order.customer', 'drawerProduct.drawer','invoice.filing'])
            ->when($keyword, function($query)use($keyword){
                return $query->whereHas('order', function($query)use($keyword){
                    $query->whereHas('drawerProducts.drawer', function ($query) use ($keyword){
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
            ->whereHas('order', function($query)use($apply_start_date, $apply_end_date){
                $query->whereBetween('orders.created_at', [$apply_start_date . ' 00:00:01', $apply_end_date . ' 23:59:59']);
            })
            ->where($where)
            ->orderBy('id', 'desc')
            //->toSql();
            ->paginate($pageSize)
            ->toArray();
    }

    public function reportExportData($param)
    {
        $where = [];
        if($param['cid'] !== ''){
            $where[] = ['cid', '=', $param['cid']];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $apply_start_date = isset($param['apply_start_date']) ? $param['apply_start_date'] : date('Y').'-01-01';
        $apply_end_date = isset($param['apply_end_date']) ? $param['apply_end_date'] : date('Y-m-d');
        return DrawerProductOrder::with(['order.customer', 'drawerProduct', 'invoice.filing'])
            ->when($keyword, function($query)use($keyword){
                return $query->whereHas('order', function($query)use($keyword){
                    $query->whereHas('drawerProducts.drawer', function ($query) use ($keyword){
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
            ->whereHas('order', function($query)use($apply_start_date, $apply_end_date){
                $query->whereBetween('orders.created_at', [$apply_start_date . ' 00:00:01', $apply_end_date . ' 23:59:59']);
            })
            ->where($where)
            ->orderBy('order_id', 'asc')
            //->toSql();
            ->get();
    }
}
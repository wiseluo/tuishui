<?php

namespace App\Repositories\Api;

use App\Clearance;

class ClearanceRepository
{
    public function clearanceIndex($param)
    {
        $where = [];
        if($param['cid']){
            $where[] = ['cid', '=', $param['cid']];
        }
        $created_user_id = $param['created_user_id'];

        $keyword = $param['keyword'];
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Clearance::when($keyword, function($query) use($keyword){
                return $query->whereHas('order', function ($query) use ($keyword){
                    $query->where('ordnumber', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
                })
                ->orWhereHas('order.drawerProducts.drawer', function ($query) use ($keyword){
                    $query->where('company', 'LIKE', '%'. $keyword .'%');
                })
                ->orWhereHas('order.customer', function ($query) use ($keyword){
                    $query->where('name', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('salesman', 'LIKE', '%'. $keyword .'%');
                })
                ->orWhereHas('order.company', function ($query) use ($keyword){
                    $query->where('invoice_name', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->whereHas('order', function($query) use($created_user_id){
                $query->where('created_user_id', $created_user_id);
            })
            ->with(['order.company', 'order.customer', 'order.declareModeData'])
            ->where($where)
            ->orderBy('id', 'DESC')
            ->paginate($pageSize)
            ->toArray();
    }

    public function getClearanceList($param)
    {
        $where = [];
        if($param['cid']){
            $where[] = ['cid', '=', $param['cid']];
        }
        $keyword = $param['keyword'];
        return Clearance::when($keyword, function($query) use($keyword){
                return $query->whereHas('order', function ($query) use ($keyword){
                    $query->where('ordnumber', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('customs_number', 'LIKE', '%'. $keyword .'%');
                })
                ->orWhereHas('order.drawerProducts.drawer', function ($query) use ($keyword){
                    $query->where('company', 'LIKE', '%'. $keyword .'%');
                })
                ->orWhereHas('order.customer', function ($query) use ($keyword){
                    $query->where('name', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('salesman', 'LIKE', '%'. $keyword .'%');
                })
                ->orWhereHas('order.company', function ($query) use ($keyword){
                    $query->where('invoice_name', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->with(['order.company', 'order.customer', 'order.declareModeData', 'order.drawerProducts'])
            ->where($where)
            ->get();
    }

    public function save($data, ...$args)
    {
        $clearance = new Clearance($data);
        $clearance->save();
        return $clearance->id;
    }

    public function update($param, $id)
    {
        $clearance = Clearance::find($id);
        return (int) $clearance->update($param);
    }

    public function delete($id)
    {
        return Clearance::destroy($id);
    }

    public function find($id)
    {
        return Clearance::find($id);
    }

    public function get($id)
    {
        return Clearance::whereId($id)->with(['order'=>function ($query) {
            $query->with(['drawerProducts' => function ($query) {
                $query->with(['drawer','product']);
            }]);
        }])->first()->append(['product_count']);
    }
}
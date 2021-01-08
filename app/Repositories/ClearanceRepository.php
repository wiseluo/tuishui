<?php

namespace App\Repositories;

use App\Clearance;

class ClearanceRepository
{
    public function clearanceIndex($param)
    {
        $where = [];
        if(isset($param['userlimit'])){
            $where = $param['userlimit'];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Clearance::where(function($query)use($keyword){
                $query->when($keyword, function($query) use($keyword){
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
                });
            })
            ->with(['order.company', 'order.customer', 'order.declareModeData', 'order.pzxsend'])
            ->where($where)
            ->orderBy('id', 'DESC')
            ->paginate($pageSize)
            ->toArray();
    }

    public function getClearanceList($param)
    {
        $where = [];
        if(isset($param['userlimit'])){
            $where = $param['userlimit'];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        return Clearance::where(function($query)use($keyword){
                $query->when($keyword, function($query) use($keyword){
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
                });
            })
            ->with(['order.company', 'order.customer', 'order.declareModeData', 'order.drawerProducts'])
            ->where($where)
            ->get();
    }

    public function save($data)
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

    public function deleteWHere($where)
    {
        return Clearance::where($where)->delete();
    }

    public function find($id)
    {
        return Clearance::find($id);
    }

    public function findByWhere($where)
    {
        return Clearance::where($where)->first();
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
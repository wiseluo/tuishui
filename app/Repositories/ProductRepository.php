<?php

namespace App\Repositories;

use App\Product;

class ProductRepository
{
    public function renderStatus()
    {
        return Product::renderStatus();
    }

    public function productIndex($param)
    {
        $where = [];
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        if($param['status']){
            $where[] = ['status', '=', $param['status']];
        }
        if(isset($param['customer_id'])){ //产品关联开票人时选择产品
            $where[] = ['customer_id', '=', $param['customer_id']];
        }
        $keyword = $param['keyword'];
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Product::with(['customer', 'measureUnitData','drawer'])
            ->where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->whereHas('customer', function ($query) use ($keyword){
                            $query->where('name', 'LIKE', '%'. $keyword .'%')
                                ->orWhere('salesman', 'LIKE', '%'. $keyword .'%');
                        })
                        ->orWhere('name', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('en_name', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('hscode', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('created_user_name', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('standard', '=', $keyword);
                });
            })
            ->where($where)
            ->where($userlimit)
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    public function getProductList($param)
    {
        $where = [];
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        $where[] = ['status', '=', 3];
        $keyword = $param['keyword'];
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Product::with(['customer', 'measureUnitData'])
            ->where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->whereHas('customer', function ($query) use ($keyword){
                            $query->where('name', 'LIKE', '%'. $keyword .'%')
                                ->orWhere('salesman', 'LIKE', '%'. $keyword .'%');
                        })
                        ->orWhere('name', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('en_name', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('hscode', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('created_user_name', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('standard', '=', $keyword);
                });
            })
            ->where($where)
            ->where($userlimit)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function save($param, ...$args)
    {
        $product = new Product($param);
        $product->save();
        return $product->id;
    }

    public function update($param, $id)
    {
        $product = Product::find($id);
        return (int) $product->update($param);
    }

    public function destroy($id)
    {
        return Product::destroy($id);
    }

    public function find($id)
    {
        return Product::find($id);
    }

    public function findWithRelation($id)
    {
        return Product::with(['link','brand','measureUnitData'])->find($id);
    }

    public function findWhere($where)
    {
        return Product::where($where)->first();
    }

    public function getProWithRelation($id)
    {
        return Product::with(['link', 'brand', 'measureUnitData'])->find($id);
    }

    public function countByWhere($where)
    {
        return Product::where($where)->count();
    }
}
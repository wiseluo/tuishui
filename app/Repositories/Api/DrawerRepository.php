<?php

namespace App\Repositories\Api;

use App\Drawer;
use App\Product;
use App\DrawerProduct;
use App\DrawerProductOrder;

class DrawerRepository
{
    public function renderStatus()
    {
        return Drawer::renderStatus();
    }

    public function drawerIndex($param)
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
        return Drawer::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->whereHas('customer', function ($query) use ($keyword){
                            $query->where('name', 'LIKE', '%'. $keyword .'%')
                                ->orWhere('salesman', 'LIKE', '%'. $keyword .'%')
                                ->orWhere('created_user_name', 'LIKE', '%'. $keyword .'%');
                        })
                        ->orWhere('company', 'LIKE', '%'. $keyword .'%')
                        ->orWhere('tax_id', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->where($where)
            ->where($userlimit)
            ->with(['customer','product'])
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    public function getDrawerList($where, $keyword)
    {
        return Drawer::search($keyword)->where($where)->get();
    }

    public function save($data, ...$args)
    {
        $drawer = new Drawer($data);
        $drawer->save();
        return $drawer->id;
    }

    public function update($params, $id)
    {
        $drawer = Drawer::find($id);
        return (int) $drawer->update($params);
    }

    public function delete($id)
    {
        return Drawer::destroy($id);
    }

    public function find($id)
    {
        return Drawer::find($id);
    }

    public function findWithRelation($id)
    {
        return Drawer::with(['product'])->find($id);
    }

    public function findWhere($where)
    {
        return Drawer::where($where)->first();
    }

    public function getDrawerProductListByCustomerIdRepository($param)
    {
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $drawer_where['customer_id'] = $param['customer_id'];
        $drawer_where['status'] = 3;
        $drawer_where['cid'] = $param['cid'];
        
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return DrawerProduct::with(['drawer', 'product'])
        ->whereHas('drawer', function($query)use($drawer_where){
            $query->where($drawer_where);
        })
        ->when($keyword, function($query)use($keyword){
            return $query->whereHas('product', function($query) use($keyword){
                    $query->where('name', 'like', '%'.$keyword.'%')
                        ->orwhere('hscode', 'like', '%'.$keyword.'%')
                        ->orwhere('standard', 'like', '%'.$keyword.'%');
                })->orWhereHas('drawer', function($query)use($keyword){
                    $query->where('company', 'like', '%'.$keyword.'%');
                });
        })
        ->orderBy('id', 'DESC')
        ->paginate($pageSize)
        ->toArray();
    }

    public function getOrderListByDrawerIdProIdsRepository($drawer_id, $product_ids)
    {
        return DrawerProductOrder::whereHas('drawerProduct', function($query) use($drawer_id, $product_ids){
            $query->where('drawer_id', $drawer_id)->whereIn('product_id', $product_ids);
        })->get();
    }

    public function getDrawerByUserId($id)
    {
        return Drawer::where('created_user_id', $id)->first();
    }

    public function getOneDrawerByWhere($where)
    {
        return Drawer::where($where)->first();
    }

    public function getDrawerProductDetail($id)
    {
        return DrawerProduct::with(['drawer', 'product.link', 'product.brand', 'product.measureUnitData'])->where('id', $id)->first();
    }

    public function getDrawerProductByProId($id)
    {
        return DrawerProduct::whereHas('product', function($query)use($id){
            $query->where('id', $id);
        })->get();
    }

    public function countByWhere($where)
    {
        return Drawer::where($where)->count();
    }
}
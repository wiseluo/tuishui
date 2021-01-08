<?php

namespace App\Repositories\Api;

use App\Brand;

class BrandRepository
{
    public function brandIndex($param)
    {
        $where = [];
        if($param['cid'] !== ''){
            $where[] = ['cid', '=', $param['cid']];
        }
        if(isset($param['created_user_id'])){
            $where[] = ['created_user_id', '=', $param['created_user_id']];
        }
        if($param['status']){
            $where[] = ['status', '=', $param['status']];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Brand::with(['link'])
        ->where(function($query)use($keyword){
            $query->when($keyword, function($query)use($keyword){
                return $query->where('name', 'LIKE', '%'. $keyword .'%');
            });
        })
        ->where($where)
        ->orderBy('id', 'desc')
        ->paginate($pageSize)
        ->toArray();
    }

    public function getBrandList($where, $param)
    {
        return Brand::search($param['keyword'])->where($where)->paginate();
    }

    public function save($param, ...$args)
    {
        $brand = new Brand($param);
        $brand->save();
        return $brand->id;
    }

    public function update($param, $id)
    {
        $brand = Brand::find($id);
        return (int) $brand->update($param);
    }

    public function delete($id)
    {
        return Brand::destroy($id);
    }

    public function find($id)
    {
        return Brand::find($id);
    }

    public function findWithRelation($id)
    {
        return Brand::with('link')->find($id);
    }

    public function countByWhere($where)
    {
        return Brand::where($where)->count();
    }
}
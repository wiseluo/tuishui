<?php

namespace App\Repositories;

use App\Brand;

class BrandRepository
{
    public function renderStatus()
    {
        return Brand::renderStatus();
    }
    
    public function brandIndex($param)
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
        return Brand::with(['link'])
            ->where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->where('name', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->where($where)
            ->where($userlimit)
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    public function save($param)
    {
        $brand = new Brand($param);
        $res = $brand->save();
        if($res){
            return $brand->id;
        }
        return 0;
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
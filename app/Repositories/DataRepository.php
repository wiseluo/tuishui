<?php

namespace App\Repositories;

use App\Data;
use Illuminate\Pagination\LengthAwarePaginator;

class DataRepository
{
    public function getTypes()
    {
        return Data::whereFatherId(0)->get();
    }

    public function getTypesByFatherId($id)
    {
        return array_column(Data::whereFatherId($id)->orderBy('sort')->get()->toArray(), 'name', 'id');
    }

    public function getTypesKeyByFatherId($id)
    {
        return array_column(Data::whereFatherId($id)->orderBy('sort')->get()->toArray(), 'key', 'id');
    }

    public function getTypesNameByFatherId($id)
    {
        return array_column(Data::whereFatherId($id)->orderBy('sort')->get()->toArray(), 'name', 'name');
    }

    public function getTypesByFatherIdAndPaginate($id, $per_page = 12)
    {
        return Data::whereFatherId($id)->orderBy('sort')->paginate($per_page);
    }

    public function update($params, $id)
    {
        $data = Data::findOrFail($id);
        return (int) $data->update($params);
    }

    public function save($params, ...$args)
    {
        $data = new Data($params);
        return (int) $data->save();
    }

    public function delete($id)
    {
        return Data::destroy($id);
    }

    public function find($id)
    {
        return Data::find($id);
    }

    public function findWhere($where)
    {
        return Data::where($where)->first();
    }

    public function unitListWithPage($param)
    {
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        return Data::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->where('name', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->where('father_id', 15)
            ->paginate(10)
            ->toArray();
    }
}
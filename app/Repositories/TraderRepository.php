<?php

namespace App\Repositories;

use App\Trader;

class TraderRepository
{
    public function traderIndex($param)
    {
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Trader::where(function($query)use($keyword){
            $query->when($keyword, function($query)use($keyword){
                return $query->whereHas('country', function ($query) use ($keyword){
                        $query->where('country_en', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('customer', function ($query) use ($keyword){
                        $query->where('name', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('salesman', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhere('name', 'LIKE', '%'. $keyword .'%');
            });
        })
        ->with('country')
        ->where($userlimit)
        ->orderBy('id', 'desc')
        ->paginate($pageSize)
        ->toArray();
    }

    public function getTraderListExportData($param)
    {
        $userlimit = [];
        if(isset($param['userlimit'])){
            $userlimit = $param['userlimit'];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Trader::where(function($query)use($keyword){
            $query->when($keyword, function($query)use($keyword){
                return $query->whereHas('country', function ($query) use ($keyword){
                        $query->where('country_en', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhereHas('customer', function ($query) use ($keyword){
                        $query->where('name', 'LIKE', '%'. $keyword .'%')
                            ->orWhere('salesman', 'LIKE', '%'. $keyword .'%');
                    })
                    ->orWhere('name', 'LIKE', '%'. $keyword .'%');
            });
        })
        ->with('country')
        ->where($userlimit)
        ->get();
    }

    public function save($data)
    {
        $trader = new Trader($data);
        $res = $trader->save();
        if($res){
            return $trader->id;
        }
        return 0;
    }

    public function update($params, $id)
    {
        $trader = Trader::find($id);
        return (int) $trader->update($params);
    }

    public function delete($id)
    {
        return Trader::destroy($id);
    }

    public function find($id)
    {
        return Trader::find($id);
    }
}
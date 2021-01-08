<?php

namespace App\Repositories;

use App\District;

class DistrictRepository
{
    public function getDistrictlist($param)
    {
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        return District::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->where('district_name', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->paginate(10)
            ->toArray();
    }

}
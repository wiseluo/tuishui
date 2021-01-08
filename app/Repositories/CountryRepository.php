<?php

namespace App\Repositories;

use App\Country;

class CountryRepository
{
    public function countryListWithPage($param)
    {
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        return Country::where(function($query)use($keyword){
                $query->when($keyword, function($query)use($keyword){
                    return $query->where('country_na', 'LIKE', '%'. $keyword .'%');
                });
            })
            ->paginate(10)
            ->toArray();
    }

    public function getCountrylist($where=[])
    {
        return array_column(Country::where($where)->get()->toArray(), 'country_na', 'id');
    }

    public function getCountryDetail($id)
    {
        return Country::with('ports')->find($id);
    }

    public function find($id)
    {
        return Country::find($id);
    }

    public function findWhere($where)
    {
        return Country::where($where)->first();
    }
}
<?php

namespace App\Services\Erp;

use App\Repositories\DataRepository;
use App\Repositories\CountryRepository;
use App\Repositories\DistrictRepository;

class CommonService
{
    public function __construct(DataRepository $dataRepository, CountryRepository $countryRepository, DistrictRepository $districtRepository)
    {
        $this->dataRepository = $dataRepository;
        $this->countryRepository = $countryRepository;
        $this->districtRepository = $districtRepository;
    }

    public function unitListService($param)
    {
        $list = $this->dataRepository->unitListWithPage($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['key'] = $value['key'];
            $item['value'] = $value['value'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function countryListService($param)
    {
        return $this->countryRepository->countryListWithPage($param);
    }

    public function districtListService($param)
    {
        return $this->districtRepository->getDistrictlist($param);
    }
}

<?php

namespace App\Services\Api;

use App\Repositories\DataRepository;
use App\Repositories\CountryRepository;
use App\Repositories\PortRepository;
use App\Repositories\DistrictRepository;

class CommonService
{
    public function __construct(DataRepository $dataRepository, CountryRepository $countryRepository, PortRepository $portRepository, DistrictRepository $districtRepository)
    {
        $this->dataRepository = $dataRepository;
        $this->countryRepository = $countryRepository;
        $this->portRepository = $portRepository;
        $this->districtRepository = $districtRepository;
    }

    public function countryIndexService($where)
    {
        return $this->countryRepository->getCountrylist($where);
    }

    public function getPortlistService($where, $country)
    {
        if($country > 0){
            $port = $this->portRepository->getPortByCountryId($country, $where);
        }else{
            $port = $this->portRepository->getChinaPort($where);
        }
        return $port;
    }

    public function countryListService($param)
    {
        return $this->countryRepository->countryListWithPage($param);
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

    public function districtListService($param)
    {
        return $this->districtRepository->getDistrictlist($param);
    }
}

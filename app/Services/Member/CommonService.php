<?php

namespace App\Services\Member;

use App\Repositories\CustomerRepository;
use App\Repositories\ProductRepository;
use App\Repositories\DrawerRepository;
use App\Repositories\OrderRepository;
use App\Repositories\BrandRepository;
use App\Repositories\PersonalRepository;
use App\Repositories\DataRepository;
use App\Repositories\CountryRepository;
use App\Repositories\DistrictRepository;

class CommonService
{
    public function __construct(CustomerRepository $customerRepository, ProductRepository $productRepository, DrawerRepository $drawerRepository, OrderRepository $orderRepository, BrandRepository $brandRepository, PersonalRepository $personalRepository, DataRepository $dataRepository, CountryRepository $countryRepository, DistrictRepository $districtRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->drawerRepository = $drawerRepository;
        $this->orderRepository = $orderRepository;
        $this->brandRepository = $brandRepository;
        $this->personalRepository = $personalRepository;
        $this->dataRepository = $dataRepository;
        $this->countryRepository = $countryRepository;
        $this->districtRepository = $districtRepository;
    }

    public function pendingReviewNumberService($param)
    {
        $count = [];
        $count['customer_count'] = $this->customerRepository->countByWhere($param);
        $count['product_count'] = $this->productRepository->countByWhere($param);
        $count['drawer_count'] = $this->drawerRepository->countByWhere($param);
        $count['order_count'] = $this->orderRepository->countByWhere($param);
        $count['brand_count'] = $this->brandRepository->countByWhere($param);
        $count['personal_count'] = $this->personalRepository->countByWhere($param);
        return $count;
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

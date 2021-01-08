<?php

namespace App\Services\Erp;

use App\Repositories\CustomerRepository;

class CustomerService
{
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function customerIndex($param)
    {
        $list = $this->customerRepository->customerIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['customer_id'] = $value['id'];
            $item['customer_name'] = $value['name'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

}

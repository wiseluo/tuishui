<?php

namespace App\Services\Erp;

use App\Repositories\CustomerfundRepository;

class CustomerfundService
{
    public function __construct(CustomerfundRepository $customerfundRepository)
    {
        $this->customerfundRepository = $customerfundRepository;
    }

    public function customerfundIndex($param)
    {
        $list = $this->customerfundRepository->customerfundIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['customer_id'] = $value['customer_id'];
            $item['customer_name'] = $value['customer_name'];
            $item['amount'] = $value['amount'];
            $item['deduct_amount'] = $value['deduct_amount'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

}

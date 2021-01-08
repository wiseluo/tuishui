<?php

namespace App\Services\Admin;

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

    public function find($id)
    {
        return $this->customerfundRepository->find($id);
    }

    public function statementIndex($param)
    {
        $list = $this->customerfundRepository->statementIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['change_amount'] = $value['change_amount'];
            $item['amount'] = $value['amount'];
            $item['deduct_amount'] = $value['deduct_amount'];
            $item['content'] = $value['content'];
            $item['style_str'] = $value['style_str'];
            $item['type_str'] = $value['type_str'];
            $item['created_at'] = $value['created_at'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function detail($id)
    {
        $customerfund = $this->customerfundRepository->find($id);
        $data = [
            'customer_id'=> $customerfund['customer_id'],
            'customer_name'=> $customerfund['customer_name'],
            'amount' => $customerfund['amount'],
            'deduct_amount' => $customerfund['deduct_amount'],

        ];
        return $data;
    }
}

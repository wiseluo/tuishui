<?php

namespace App\Services\Api;

use App\Repositories\Api\ClearanceRepository;
use DB;

class ClearanceService
{
    protected $orderRepository;

    public function __construct(ClearanceRepository $clearanceRepository)
    {
        $this->clearanceRepository = $clearanceRepository;
    }

    public function clearanceIndex($param)
    {
        $list = $this->clearanceRepository->clearanceIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['order_id'] = $value['order']['id'];
            $item['ordnumber'] = $value['order']['ordnumber'];
            $item['status_str'] = $value['order']['status_str'];
            $item['customer_name'] = $value['order']['customer']['name'];
            $item['company_name'] = $value['order']['company']['name'];
            $item['declare_mode_name'] = $value['order']['declare_mode_data']['name'];
            $item['customs_number'] = $value['order']['customs_number'];
            $item['clearance_status'] = $value['order']['clearance_status'];
            $item['generator'] = $value['generator'];
            $item['prerecord'] = $value['prerecord'];
            $item['transport'] = $value['transport'];
            $item['release'] = $value['release'];
            $item['lading'] = $value['lading'];
            $item['declare'] = $value['declare'];
            $item['other_info'] = $value['other_info'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function update($param, $id)
    {
        $res = $this->clearanceRepository->update($param, $id);
        if($res){
            return ['status'=>'1', 'msg'=>'成功'];
        }else{
            return ['status'=>'0', 'msg'=> '失败'];
        }
    }

    public function find($id)
    {
        return $this->clearanceRepository->find($id);
    }

    public function get($id)
    {
        return $this->clearanceRepository->get($id);
    }

}

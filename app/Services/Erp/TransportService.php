<?php

namespace App\Services\Erp;

use App\Repositories\TransportRepository;
use App\Repositories\OrderRepository;
use DB;

class TransportService
{
    public function __construct(TransportRepository $transportRepository, OrderRepository $orderRepository)
    {
        $this->transportRepository = $transportRepository;
        $this->orderRepository = $orderRepository;
    }

    public function orderList($param)
    {
        $list = $this->orderRepository->TransportOrderChoose($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['ordnumber'] = $value['ordnumber'];
            $item['customer_name'] = $value['customer']['name'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function transportAddService($param, $order)
    {
        DB::beginTransaction();
        $transport = [];
        if($param['tax_type'] == 0){
            $transport['cid'] = 0;
        }else{
            $transport['cid'] = $param['company_id'];
        }
        $transport['erp_id'] = $param['id'];
        $transport['company_id'] = $param['company_id'];
        $transport['name'] = $param['name'];
        $transport['number'] = $param['number'];
        $transport['money'] = $param['money'];
        $transport['billed_at'] = $param['billed_at'];
        $transport['applied_at'] = $param['applied_at'];
        $transport['status'] = 3;
        $transport['approved_at'] = date('Y-m-d');
        $transport['created_user_id'] = 0;
        $transport['created_user_name'] = $param['create_username'];
        //保存图片
        $transport['picture'] = savePicFromErpFunc($param['pics']);
        
        $transport_id = $this->transportRepository->save($transport);
        if($transport_id){
            $this->transportRepository->relateOrder($transport_id, $order);
            DB::commit();
            return ['status'=>'1', 'msg'=> '添加发票成功'];
        }else{
            DB::rollback();
            return ['status'=>'0', 'msg'=> '添加发票失败'];
        }
    }
}

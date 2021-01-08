<?php

namespace App\Services\Api;

use App\Repositories\OrderRepository;
use App\Repositories\ClearanceRepository;
use App\Repositories\SettlementRepository;
use App\Repositories\DataRepository;
use DB;

class OrderService
{
    public function __construct(OrderRepository $orderRepository, ClearanceRepository $clearanceRepository, SettlementRepository $settlementRepository, DataRepository $dataRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->clearanceRepository = $clearanceRepository;
        $this->settlementRepository = $settlementRepository;
        $this->dataRepository = $dataRepository;
    }

    public function getOrderAttributes()
    {
        $clearance_port = $this->dataRepository->getTypesByFatherId(2);
        $declare_mode = $this->dataRepository->getTypesByFatherId(4);
        $currency = $this->dataRepository->getTypesByFatherId(5);
        $price_clause = $this->dataRepository->getTypesByFatherId(6);
        $loading_mode = $this->dataRepository->getTypesByFatherId(8);
        $package = $this->dataRepository->getTypesByFatherId(7);
        $order_package = $this->dataRepository->getTypesByFatherId(9);
        $business = $this->dataRepository->getTypesByFatherId(11);
        $transport = $this->dataRepository->getTypesByFatherId(16);
        $unit = $this->dataRepository->getTypesByFatherId(15);

        return compact('clearance_port',
                'declare_mode',
                'currency',
                'price_clause',
                'loading_mode',
                'package',
                'order_package',
                'business',
                'transport',
                'unit'
            );
    }

    public function find($id)
    {
        return $this->orderRepository->find($id);
    }

    public function showService($id)
    {
        return $this->orderRepository->findWithRelation($id);
    }

    public function orderIndex($param)
    {
        $list = $this->orderRepository->orderIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['ordnumber'] = $value['ordnumber'];
            $item['status'] = $value['status'];
            $item['customer_name'] = $value['customer']['name'];
            $item['clearance_status'] = $value['clearance_status'];
            $item['declare_mode_name'] = $value['declare_mode_data']['name'];
            $item['company_name'] = $value['company']['name'];
            $item['total_value_invoice'] = $value['total_value_invoice'];
            $item['tdnumber'] = $value['tdnumber'];
            $item['status_str'] = $value['status_str'];
            $item['created_at'] = $value['created_at'];
            $item['invoice_complete_str'] = $value['invoice_complete_str'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function orderDate($data, $pro)
    {
        $order = $data;
        if(count($pro) == 0){
            return $order;
        }
        //总产品数量
        $order['total_num'] = array_reduce($pro, function($carry, $item){
            return bcadd($carry, $item['number'], 2);
        });
        //总货值
        $order['total_value'] = array_reduce($pro, function($carry, $item){
            return bcadd($carry, $item['total_price'], 4);
        });
        //总开票金额
        $order['total_value_invoice'] = array_reduce($pro, function($carry, $item){
            return bcadd($carry, $item['value'], 4);
        });
        //总毛重
        $order['total_weight'] = array_reduce($pro, function($carry, $item){
            return bcadd($carry, $item['total_weight'], 2);
        });
        //总净重
        $order['total_net_weight'] = array_reduce($pro, function($carry, $item){
            return bcadd($carry, $item['net_weight'], 2);
        });
        //总体积
        $order['total_volume'] = array_reduce($pro, function($carry, $item){
            return bcadd($carry, $item['volume'], 2);
        });
        //总件数(CTNS) 运输包装件数
        $order['total_packnum'] = array_reduce($pro, function($carry, $item){
            return bcadd($carry, $item['pack_number']);
        });
        return $order;
    }

    public function saveWithPro($param, $pro)
    {
        $ord = $this->orderDate($param, $pro);
        DB::beginTransaction();
        $ord['ordnumber'] = $this->orderRepository->getNextOrdnumber($param['company_id']);
        $id = $this->orderRepository->save($ord, $pro);
        if($id){
            $this->orderRepository->relateProduct($id, $pro);
            DB::commit();
            return ['status'=>'1', 'msg'=> '添加成功'];
        }else{
            DB::rollback();
            return ['status'=>'0', 'msg'=> '添加失败'];
        }
    }

    public function updateWithPro($param, $id, $pro)
    {
        $ord = $this->orderDate($param, $pro);
        DB::beginTransaction();
        $order_res = $this->orderRepository->update($ord, $id);
        if($order_res){
            $this->orderRepository->relateProduct($id, $pro);
            DB::commit();
            return ['status'=>'1', 'msg'=> '修改成功'];
        }else{
            DB::rollback();
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }

    public function retrieve($id)
    {
        $drawer = $this->orderRepository->find($id);
        if(!$drawer){
            return ['status'=>'0', 'msg'=>'该订单不存在'];
        }else if($drawer->status > 4){
            return ['status'=>'0', 'msg'=>'该订单状态不能取回'];
        }
        $res = $this->orderRepository->update(['status'=> 1], $id);
        if($res){
            return ['status'=>'1', 'msg'=>'取回成功'];
        }else{
            return ['status'=>'0', 'msg'=> '取回失败'];
        }
    }

    public function delete($id)
    {
        $order = $this->orderRepository->find($id);
        if(!$order){
            return ['status'=>'0', 'msg'=>'该订单不存在'];
        }else if($order->status != 1){
            return ['status'=>'0', 'msg'=>'该订单不是草稿，不能删除'];
        }
        $res = $this->orderRepository->delete($id);
        if($res){
            return ['status'=>'1', 'msg'=> '删除成功'];
        }else{
            return ['status'=>'0', 'msg'=> '删除失败'];
        }
    }

}

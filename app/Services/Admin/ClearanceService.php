<?php

namespace App\Services\Admin;

use App\Repositories\OrderRepository;
use App\Repositories\ClearanceRepository;
use App\Repositories\SettlementRepository;
use App\Repositories\DrawerProductOrderRepository;
use Illuminate\Support\Facades\DB;

class ClearanceService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository, ClearanceRepository $clearanceRepository, SettlementRepository $settlementRepository, DrawerProductOrderRepository $drawerProductOrderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->clearanceRepository = $clearanceRepository;
        $this->settlementRepository = $settlementRepository;
        $this->drawerProductOrderRepository = $drawerProductOrderRepository;
    }

    public function clearanceIndex($param)
    {
        $list = $this->clearanceRepository->clearanceIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['order_id'] = $value['order']['id'];
            $item['ordnumber'] = $value['order']['ordnumber'];
            $item['status'] = $value['order']['status'];
            $item['status_str'] = $value['order']['status_str'];
            $item['customer_name'] = $value['order']['customer']['name'];
            $item['company_name'] = $value['order']['company']['name'];
            $item['declare_mode_name'] = $value['order']['declare_mode_data']['name'];
            $item['customs_number'] = $value['order']['customs_number'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function update($param, $id)
    {
        $res = $this->clearanceRepository->update($param, $id);
        if($res){
            return ['status'=> 1, 'msg'=> '操作成功'];
        }else{
            return ['status'=> 0, 'msg'=> '操作失败'];
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

    public function customsEntryUpdate($param, $pro, $id) //报关录入
    {
        DB::beginTransaction();
        $order_data = $param;
        $order_data['status'] = 5; //已报关
        $order_res = $this->orderRepository->update($order_data, $id);
        foreach ($pro as $k => $v) {
            $dp_data = $this->drawerProductOrderRepository->find($v['drawer_product_order_id']);
            //换汇成本= (开票金额/(1+开票人增值税率%))*(1+开票人增值税率%-产品退税率%)/货值
            $exchange_cost = bcdiv(bcmul(bcdiv($v['value'], bcadd(1, $dp_data['tax_rate'], 2), 2), bcsub(bcadd(1, $dp_data['tax_rate'], 2), bcdiv($dp_data['tax_refund_rate'], 100, 2), 2)), $dp_data['total_price'], 2);
            $drawer_product_data = [
                'value' => $v['value'],
                'exchange_cost' => $exchange_cost,
            ];
            $drawer_product_order_res = $this->drawerProductOrderRepository->update($drawer_product_data, $v['drawer_product_order_id']);
            if($drawer_product_order_res == null) {
                DB::rollback();
                return ['status'=> 0, 'msg'=> '产品:'. $v['drawer_product_order_id'] .'修改失败'];
            }
        }
        $settlement = $this->settlementRepository->findByWhere(['order_id'=> $id]);
        if(!$settlement){
            $settlement_res = $this->settlementRepository->save(['order_id'=> $id]);
            if($settlement_res == false){
                DB::rollback();
                return ['status'=> 0, 'msg'=> '退税结算单生成失败'];
            }
        }

        if($order_res && $settlement_res){
            DB::commit();
            return ['status'=> 1, 'msg'=> '修改成功'];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg'=> '修改失败'];
        }
    }

}

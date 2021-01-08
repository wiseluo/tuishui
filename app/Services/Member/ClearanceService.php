<?php

namespace App\Services\Member;

use App\Repositories\OrderRepository;
use App\Repositories\ClearanceRepository;
use App\Repositories\SettlementRepository;
use App\Services\Zjport\ZjportService;
use App\Services\External\ExternalService;
use App\Repositories\DrawerProductOrderRepository;
use DB;

class ClearanceService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository, ClearanceRepository $clearanceRepository, SettlementRepository $settlementRepository, ZjportService $zjportService, ExternalService $externalService, DrawerProductOrderRepository $drawerProductOrderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->clearanceRepository = $clearanceRepository;
        $this->settlementRepository = $settlementRepository;
        $this->zjportService = $zjportService;
        $this->externalService = $externalService;
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
            $item['pzxsend'] = $value['order']['pzxsend'];
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

    public function customsEntryUpdate($param, $pro, $id) //报关录入，订单id
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

        //商翔通和聚达通接口
        $external_res = $this->externalService->sendOrder($id);
        //dd($external_res);
        if($order_res){
            DB::commit();
            return ['status'=> 1, 'msg'=> '操作成功'];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg'=> '操作失败'];
        }
    }

    public function pzxSenderOrder($order_id)
    {
        DB::beginTransaction();
        $order = $this->orderRepository->find($order_id);
        if(!$order){
            return ['status'=> 0, 'msg'=> '订单不存在'];
        }
        //品浙行
        $scmOrder_res = $this->zjportService->ScmOrder($order_id);

        if($scmOrder_res['code']){
            DB::commit();
            return ['status'=> 1, 'msg'=> '操作成功'.$scmOrder_res['msg']];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg'=> $scmOrder_res['msg']];
        }
    }

    public function pzxSenderClearance($order_id)
    {
        DB::beginTransaction();
        $order = $this->orderRepository->find($order_id);
        if(!$order){
            return ['status'=> 0, 'msg'=> '订单不存在'];
        }
        //品浙行
        $expCustoms_res = $this->zjportService->ExpCustoms($order_id);

        if($expCustoms_res['code']){
            DB::commit();
            return ['status'=> 1, 'msg'=> '操作成功'.$expCustoms_res['msg']];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg'=> '操作失败'.$expCustoms_res['msg']];
        }
    }
}

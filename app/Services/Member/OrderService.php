<?php

namespace App\Services\Member;

use App\Repositories\OrderRepository;
use App\Repositories\OrderlogRepository;
use App\Repositories\ClearanceRepository;
use App\Repositories\SettlementRepository;
use App\Repositories\DataRepository;
use App\Repositories\NotificationRepository;
use DB;

class OrderService
{
    protected $order_value = [
        'exchange_method' => '收汇方式',
        'lc_no' => '信用证号',
        'clearance_port' => '报关口岸',
        'declare_mode' => '报关方式',
        'broker_name' => '报关行名称',
        'business' => '业务类型',
        'loading_mode' => '装柜方式',
        'box_number' => '货柜箱号',
        'tdnumber' => '提单号',
        'ship_name' => '船名航次',
        'currency' => '报关币种',
        'price_clause' => '价格条款',
        'order_package' => '整体包装方式',
        'shipment_port' => '离境口岸',
        'aim_country' => '运抵国',
        'unloading_port' => '抵运港',
        'transport' => '运输方式',
        'total_num' => '产品总数量',
        'total_value' => '总货值',
        'total_weight' => '总毛重',
        'total_net_weight' => '总净重',
        'total_volume' => '总体积',
        'total_packnum' => '运输包装件数',
        'other_packages_type' => '其他包装种类',
        'package' => '包装种类',
        'shipping_at' => '预计出货日期',
        'sailing_at' => '开船日期',
        'is_special' => '特殊关系确认',
        'is_pay_special' => '支付特许权使用费确认',
    ];

    protected $pro_value = [
        'number' => '产品数量',
        'unit' => '单位',
        'single_price' => '单价',
        'total_price' => '货值',
        'default_num' => '法定数量',
        'default_unit' => '法定单位',
        'value' => '开票金额',
        'volume' => '体积',
        'net_weight' => '净重',
        'total_weight' => '毛重',
        'pack_number' => '件数',
        'domestic_source' => '境内货源地',
        'production_place' => '产地',
        'origin_country' => '原产国',
        'destination_country' => '最终目的国',
        'goods_attribute' => '货物属性',
        'ciq_code' => 'CIQ编码',
        'species' => '种类',
        'surface_material' => '表面材质',
        'section_number' => '款号',
        'enjoy_offer' => '是否享受优惠关税',
        'tax_refund_rate' => '退税率',
    ];

    public function __construct(OrderRepository $orderRepository, OrderlogRepository $orderlogRepository, ClearanceRepository $clearanceRepository, SettlementRepository $settlementRepository, DataRepository $dataRepository, NotificationRepository $notificationRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderlogRepository = $orderlogRepository;
        $this->clearanceRepository = $clearanceRepository;
        $this->settlementRepository = $settlementRepository;
        $this->dataRepository = $dataRepository;
        $this->notificationRepository = $notificationRepository;
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

    public function renderStatus()
    {
        return $this->orderRepository->renderStatus();
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
            $item['invoice_complete_str'] = $value['invoice_complete'] == 0 ? '未完成' : '已完成';
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function orderDate($data, $pro)
    {
        $order = $data;
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

    public function update($param, $id)
    {
        $order_res = $this->orderRepository->update($param, $id);
        if($order_res){
            return ['status'=>'1', 'msg'=> '修改成功'];
        }else{
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }
    
    public function approve($param, $id)
    {
        DB::beginTransaction();
        //消息提醒
        $order = $this->orderRepository->find($id);
        $data = [];
        $data['cid'] = $param['cid'];
        $data['receiver_id'] = $order->created_user_id;
        $data['content'] = '您提交的【订单管理】'. $order->ordnumber;
        if($param['status'] == 3){
            $clearance = $this->clearanceRepository->findByWhere(['order_id'=> $id]);
            if(!$clearance){
                $clearance_res = $this->clearanceRepository->save(['order_id'=> $id]);
                if($clearance_res == false){
                    DB::rollback();
                    return ['status'=>'0', 'msg'=> '报关单生成失败'];
                }
            }
            $data['content'] .= '已审核通过';
        }else{
            $data['content'] .= '被审核拒绝';
        }
        $this->notificationRepository->save($data);
        $order_res = $this->orderRepository->update($param, $id);
        if($order_res){
            DB::commit();
            return ['status'=>'1', 'msg'=> '修改成功'];
        }else{
            DB::rollback();
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }

    public function retrieve($id)
    {
        $order = $this->orderRepository->find($id);
        if(!$order){
            return ['status'=>'0', 'msg'=>'该订单不存在'];
        }else if($order->status > 4){
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

    public function find($id)
    {
        return $this->orderRepository->find($id);
    }
    
    //订单修改了哪些内容
    public function order_diff_update($old_arr, $new_arr)
    {
        $content = '';
        $diff = array_diff_assoc($old_arr, $new_arr);
        foreach($diff as $k => $v){
            if(array_key_exists($k, $this->order_value)){
                $content .= $this->order_value[$k].':原值为'.$old_arr[$k].',修改为'.$new_arr[$k].';';
            }
        }
        return $content == '' ? '未修改任何内容' : $content;
    }
    //产品修改了哪些内容
    public function pro_diff_update($old_arr, $new_arr)
    {
        $content = '';
        $old_ids = array_column($old_arr, 'id');
        $new_ids = array_column($new_arr, 'id');
        $del_ids = array_diff($old_ids, $new_ids); //差集,删除开票产品id
        $add_ids = array_diff($new_ids, $old_ids); //差集,新增开票产品id
        $edit_ids = array_intersect($old_ids, $new_ids); //交集,相同开票产品id
        if(count($del_ids)){
            $del_ids_str = implode(',', $del_ids);
            $content .= '删除了开票产品：'.$del_ids_str.';';
        }
        if(count($add_ids)){
            $add_ids_str = implode(',', $add_ids);
            $content .= '添加了开票产品：'.$add_ids_str.';';
        }

        $old_pivot = array_column($old_arr, 'pivot', 'id');
        $new_pivot = array_column($new_arr, 'pivot', 'id');
        foreach($edit_ids as $m => $n){
            $content .= '修改了开票产品：'.$n.'{';
            $old_pro = $old_pivot[$n];
            $new_pro = $new_pivot[$n];
            $diff = array_diff_assoc($old_pro, $new_pro); //差集（比较键名和键值）
            foreach($diff as $k => $v){
                if(array_key_exists($k, $this->pro_value)){
                    $content .= $this->pro_value[$k].':原值为'.$old_pro[$k].',修改为'.$new_pro[$k].';';
                }
            }
            $content .= '};';
        }
        return $content == '' ? '未修改任何内容' : $content;
    }

    public function closeCase($param) //结案
    {
        DB::beginTransaction();
        $order_res = $this->orderRepository->update(['status'=> 5], $param['id']);
        $settlement_res = $this->settlementRepository->save(['order_id'=> $param['id']]);

        if($order_res && $settlement_res){
            DB::commit();
            return ['status'=>'1', 'msg'=> '结案成功'];
        }else{
            DB::rollback();
            return ['status'=>'0', 'msg'=> '结案失败'];
        }
    }
}

<?php

namespace App\Services\Admin;

use App\Repositories\OrderRepository;
use App\Repositories\ClearanceRepository;
use App\Repositories\SettlementRepository;
use App\Repositories\DataRepository;
use App\Repositories\CountryRepository;
use App\Repositories\PortRepository;
use App\Repositories\DrawerProductOrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(OrderRepository $orderRepository, ClearanceRepository $clearanceRepository, SettlementRepository $settlementRepository, DataRepository $dataRepository, CountryRepository $countryRepository,PortRepository $portRepository,DrawerProductOrderRepository $drawerProductOrderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->clearanceRepository = $clearanceRepository;
        $this->settlementRepository = $settlementRepository;
        $this->dataRepository = $dataRepository;
        $this->countryRepository = $countryRepository;
        $this->portRepository = $portRepository;
        $this->drawerProductOrderRepository = $drawerProductOrderRepository;
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
        $shipment_port = $this->portRepository->getChinaPort();
        $trade_country = $this->countryRepository->getCountrylist();
        $aim_country = $this->countryRepository->getCountrylist();
        
        return compact('clearance_port',
                'declare_mode',
                'currency',
                'price_clause',
                'loading_mode',
                'package',
                'order_package',
                'business',
                'transport',
                'unit',
                'shipment_port',
                'trade_country',
                'aim_country'
            );
    }

    public function harborService($id)
    {
        return $this->countryRepository->getCountryDetail($id);
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
            return bcadd($carry, $item['total_weight'], 4);
        });
        //总净重
        $order['total_net_weight'] = array_reduce($pro, function($carry, $item){
            return bcadd($carry, $item['net_weight'], 4);
        });
        //总体积
        $order['total_volume'] = array_reduce($pro, function($carry, $item){
            return bcadd($carry, $item['volume'], 3);
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
        $id = $this->orderRepository->save($ord);
        if($id){
            $this->orderRepository->relateProduct($id, $pro);
            DB::commit();
            return ['status'=> 1, 'msg'=> '添加成功'];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg'=> '添加失败'];
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
            return ['status'=> 1, 'msg'=> '修改成功'];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg'=> '修改失败'];
        }
    }

    public function update($param, $id)
    {
        $order_res = $this->orderRepository->update($param, $id);
        if($order_res){
            return ['status'=> 1, 'msg'=> '修改成功'];
        }else{
            return ['status'=> 0, 'msg'=> '修改失败'];
        }
    }

    public function approve($param, $id)
    {
        DB::beginTransaction();
        if($param['status'] == 3){ //审批通过
            $clearance = $this->clearanceRepository->findByWhere(['order_id'=> $id]);
            if(!$clearance){
                $clearance_res = $this->clearanceRepository->save(['order_id'=> $id]);
                if($clearance_res == false){
                    DB::rollback();
                    return 0;
                }
            }
        }
        $order_res = $this->orderRepository->update($param, $id);
        if($order_res){
            DB::commit();
            return ['status'=> 1, 'msg'=> '修改成功'];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg'=> '修改失败'];
        }
    }

    public function retrieve($id)
    {
        $order = $this->orderRepository->find($id);
        if(!$order){
            return ['status'=> 0, 'msg'=>'该订单不存在'];
        }else if($order->status > 4){
            return ['status'=> 0, 'msg'=>'该订单状态不能取回'];
        }
        $res = $this->orderRepository->update(['status'=> 1], $id);
        if($res){
            return ['status'=> 1, 'msg'=>'取回成功'];
        }else{
            return ['status'=> 0, 'msg'=> '取回失败'];
        }
    }

    public function delete($id)
    {
        $order = $this->orderRepository->find($id);
        if(!$order){
            return ['status'=> 0, 'msg'=>'该订单不存在'];
        }else if($order->status != 1){
            return ['status'=> 0, 'msg'=>'该订单不是草稿，不能删除'];
        }
        $res = $this->orderRepository->delete($id);
        //$this->clearanceRepository->deleteWHere(['order_id'=> $id]);
        if($res){
            return ['status'=> 1, 'msg'=> '删除成功'];
        }else{
            return ['status'=> 0, 'msg'=> '删除失败'];
        }
    }

    public function find($id)
    {
        return $this->orderRepository->find($id);
    }

    public function closeCase($param) //结案
    {
        DB::beginTransaction();
        $order_res = $this->orderRepository->update(['status'=> 5], $param['id']);
        $settlement_res = $this->settlementRepository->save(['order_id'=> $param['id']]);

        if($order_res && $settlement_res){
            DB::commit();
            return ['status'=> 1, 'msg'=> '结案成功'];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg'=> '结案失败'];
        }
    }

    public function customsOrderSaveService($param, $pro)
    {
        $param['status'] = 2;
        $ord = $this->orderDate($param, $pro);
        DB::beginTransaction();
        $order_id = $this->orderRepository->save($ord);
        if($order_id){
            foreach ($pro as $k => $v) {
                $measure_unit = $this->dataRepository->find($v['measure_unit']);
                $drawer_product_data = [
                    'order_id' => $order_id,
                    'drawer_product_id' => $v['drawer_product_id'],
                    'standard' => $v['standard'],
                    'company' => $v['company'],
                    'number' => $v['number'],
                    'unit' => $v['unit'],
                    'single_price' => $v['single_price'],
                    'total_price' => $v['total_price'],
                    'default_num' => $v['default_num'],
                    'default_unit' => $v['default_unit'],
                    'volume' => $v['volume'],
                    'net_weight' => $v['net_weight'],
                    'total_weight' => $v['total_weight'],
                    'pack_number' => $v['pack_number'],
                    'measure_unit' => $v['measure_unit'],
                    'measure_unit_cn' => $measure_unit->name,
                    'measure_unit_en' => $measure_unit->value,
                    'merge' => $v['merge'],
                    'domestic_source_id' => $v['domestic_source_id'],
                    'domestic_source' => $v['domestic_source'],
                    'production_place' => $v['production_place'],
                    'origin_country_id' => $v['origin_country_id'],
                    'origin_country' => $v['origin_country'],
                    'destination_country_id' => $v['destination_country_id'],
                    'destination_country' => $v['destination_country'],
                    'goods_attribute' => $v['goods_attribute'],
                    'ciq_code' => $v['ciq_code'],
                    'species' => $v['species'],
                    'surface_material' => $v['surface_material'],
                    'section_number' => $v['section_number'],
                    'enjoy_offer' => $v['enjoy_offer'],
                    'tax_refund_rate' => $v['tax_refund_rate'],
                    'tax_rate' => $v['tax_rate'],
                    'value' => $v['value'],
                ];
                $drawer_product_order_id = $this->drawerProductOrderRepository->save($drawer_product_data);
                if($drawer_product_order_id == null) {
                    DB::rollback();
                    return ['status'=> 0, 'msg'=> '产品:'. $v['drawer_product_id'] .'添加失败'];
                }
            }
            DB::commit();
            return ['status'=> 1, 'msg'=> '添加成功'];
        }else{
            DB::rollback();
            return ['status'=> 0, 'msg'=> '添加失败'];
        }
    }
    public function fieldMatchIdService($param, $pro)
    {
        $company = $this->orderRepository->matchCompanyRepository(['name' => $param['company']]);
        $customer = $this->orderRepository->matchCustomerRepository(['cid' => 0,'status' => 3,'name' => $param['customer_name']]);
        $trader = $this->orderRepository->matchTraderRepository(['name' => $param['trader_name']]);
        $unloading_port = $this->portRepository->findWhere(['port_c_cod' => $param['unloading_port_name']]);
        $aim_country = $this->countryRepository->findWhere(['country_na' => $param['aim_country_name']]);
        $price_clause = $this->dataRepository->findWhere([['father_id', '=', 6], ['name', 'like', '%'. $param['price_clause_name'].'%']]);
        $package = $this->dataRepository->findWhere(['father_id' => 7, 'name' => $param['package_name']]);
        $transport = $this->dataRepository->findWhere(['father_id' => 16, 'name' => $param['transport_name']]);
        $clearance_port = $this->dataRepository->findWhere(['father_id' => 2, 'name' => $param['clearance_port_name']]);
        $product = [];
        foreach ($pro as $k => $v) {
            $drawer = $this->orderRepository->matchDrawerRepository(['cid'=> 0,'status'=> 3,'company'=> $v['drawer_name']]);
            $measure_unit = $this->dataRepository->findWhere(['father_id'=> 15, 'name' => $v['measure_unit_cn']]);
            $default_unit = $this->dataRepository->findWhere(['father_id'=> 15, 'name' => $v['default_unit_name']]);
            $destination_country = $this->countryRepository->findWhere(['country_na' => $v['destination_country']]);
            $domestic_source = $this->orderRepository->matchDomesticSourceRepository(['district_name' => $v['domestic_source']]);
            $product[] = [
                'drawer_id' => $drawer ? $drawer->id : '',
                'measure_unit' => $measure_unit ? $measure_unit->id : '',
                'default_unit_id' => $default_unit ? $default_unit->id : '',
                'destination_country_id' => $destination_country ? $destination_country->id : '',
                'domestic_source_id' => $domestic_source ? $domestic_source->id : '',
            ];
        }
        return [
            'company_id' => $company ? $company->id : '',
            'customer_id' => $customer ? $customer->id : '',
            'trader_id' => $trader ? $trader->id : '',
            'unloading_port' => $unloading_port ? $unloading_port->id : '',
            'aim_country' => $aim_country ? $aim_country->id : '',
            'price_clause' => $price_clause ? $price_clause->id : '',
            'package' => $package ? $package->id : '',
            'transport' => $transport ? $transport->id : '',
            'clearance_port' => $clearance_port ? $clearance_port->id : '',
            'pro' => $product,
        ];
    }
}

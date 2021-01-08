<?php

namespace App\Services\Api;

use App\Repositories\Api\DrawerRepository;

class DrawerService
{
    public function __construct(DrawerRepository $drawerRepository)
    {
        $this->drawerRepository = $drawerRepository;
    }

    public function drawerIndex($param)
    {
        $list = $this->drawerRepository->drawerIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['status'] = $value['status'];
            $item['company'] = $value['company'];
            $item['tax_id'] = $value['tax_id'];
            $item['created_at'] = $value['created_at'];
            $item['status_str'] = $value['status_str'];
            $item['customer_name'] = $value['customer']['name'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function find($id)
    {
        return $this->drawerRepository->find($id);
    }

    public function showService($id)
    {
        return $this->drawerRepository->findWithRelation($id);
    }

    public function update($param, $id)
    {
        $res = $this->drawerRepository->update($param, $id);
        if($res){
            return ['status'=>'1', 'msg'=> '修改成功'];
        }else{
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }

    public function retrieve($id)
    {
        $drawer = $this->drawerRepository->find($id);
        if(!$drawer){
            return ['status'=>'0', 'msg'=> '该开票人不存在'];
        }else if($drawer->status != 2){
            return ['status'=>'0', 'msg'=> '该开票人状态不能取回'];
        }
        $res = $this->drawerRepository->update(['status'=> 1], $id);
        if($res){
            return ['status'=>'1', 'msg'=> '取回成功'];
        }else{
            return ['status'=>'0', 'msg'=> '取回失败'];
        }
    }

    public function destroy($id)
    {
        $res = $this->drawerRepository->destroy($id);
        if($res){
            return ['status'=>'1', 'msg'=> '删除成功'];
        }else{
            return ['status'=>'0', 'msg'=> '删除失败'];
        }
    }

    public function relateProducts($param, $id)
    {
        $drawer = $this->drawerRepository->find($id);
        if($drawer){
            if(count($param['pid']) == 0){
                $drawer->product()->detach();
                return ['status'=>'1', 'msg'=>'成功'];
            }
            $products = $param['pid'];
            if($drawer->product()->get()->isEmpty()){
                $drawer->product()->attach($products);
                return ['status'=>'1', 'msg'=>'成功'];
            }else{
                $product_ids = $drawer->product()->get()->map(function($item){
                    return $item->id;
                })->diff($products);
                
                $drawer_product_order = $this->drawerRepository->getOrderListByDrawerIdProIdsRepository($id, $product_ids);
                
                if ($drawer_product_order->isEmpty()) {
                    $now = $drawer->product()->pluck('products.id')->toArray();
                    $drawer->product()->attach(array_diff($products, $now));
                    $drawer->product()->detach(array_diff($now, $products));
                    return ['status'=>'1', 'msg'=>'成功'];
                }else {
                    return ['status'=>'0', 'msg'=>'不能删除已添加到订单中的产品'];
                }
            }
        }else{
            return ['status'=>'0', 'msg'=>'开票人不存在'];
        }
    }

    public function getDrawerProductListByCustomerId($param)
    {
        $list =  $this->drawerRepository->getDrawerProductListByCustomerIdRepository($param);
        $data = array_map(function($value){
            $item = [];
            $item['drawer_product_id'] = $value['id'];
            $item['product_id'] = $value['product']['id'];
            $item['picture'] = $value['product']['picture'];
            $item['name'] = $value['product']['name'];
            $item['hscode'] = $value['product']['hscode'];
            $item['standard'] = $value['product']['standard'];
            $item['tax_refund_rate'] = $value['product']['tax_refund_rate'];
            $item['number'] = $value['product']['number'];
            $item['company'] = $value['drawer']['company'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }
    
    public function getDrawerProductDetail($id)
    {
        $detail = $this->drawerRepository->getDrawerProductDetail($id);

        $data = [
            'drawer_product_id' => $detail->id,
            'product_id' => $detail->product->id,
            'name' => $detail->product->name,
            'en_name' => $detail->product->en_name,
            'hscode' => $detail->product->hscode,
            'standard' => $detail->product->standard,
            'tax_refund_rate' => $detail->product->tax_refund_rate,
            'tax_rate' => $detail->drawer->tax_rate,
            'measure_unit_cn' => isset($detail->product->measureUnitData->name) ? $detail->product->measureUnitData->name : '',
            'measure_unit' => $detail->product->measure_unit,
            'brand_classify_str' => isset($detail->product->brand->classify_str) ? $detail->product->brand->classify_str : '',
            'brand_type_str' => isset($detail->product->brand->type_str) ? $detail->product->brand->type_str : '',
            'domestic_source_id' => $detail->drawer->domestic_source_id,
            'domestic_source' => $detail->drawer->domestic_source,
            'company' => $detail->drawer->company,
            'unit' => $detail->product->unit,
        ];
        return $data;
    }

    public function nameMatch($where)
    {
        $detail = $this->drawerRepository->findWhere($where);
        return $detail;
    }
}

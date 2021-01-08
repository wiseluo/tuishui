<?php

namespace App\Services\Api;

use App\Repositories\ProductRepository;
use App\Repositories\DrawerRepository;

class ProductService
{
    public function __construct(ProductRepository $productRepository, DrawerRepository $drawerRepository)
    {
        $this->productRepository = $productRepository;
        $this->drawerRepository = $drawerRepository;
    }
    
    public function renderStatus()
    {
        return $this->productRepository->renderStatus();
    }

    public function productIndex($param)
    {
        $list = $this->productRepository->productIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['created_user_name'] = $value['created_user_name'];
            $item['name'] = $value['name'];
            $item['en_name'] = $value['en_name'];
            $item['hscode'] = $value['hscode'];
            $item['standard'] = $value['standard'];
            $item['tax_refund_rate'] = $value['tax_refund_rate'];
            $item['number'] = $value['number'];
            $item['measure_unit'] = $value['measure_unit_data']['name'];
            $item['status'] = $value['status'];
            $item['status_str'] = $value['status_str'];
            $item['picture'] = $value['picture'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function find($id)
    {
        return $this->productRepository->find($id);
    }

    public function showService($id)
    {
        return $this->productRepository->findWithRelation($id);
    }
    
    //保存验证申报要素
    public function saveProcess($param)
    {
        $where[] = ['customer_id', '=', $param['customer_id']];
        $where[] = ['name', '=', $param['name']];
        $where[] = ['hscode', '=', $param['hscode']];
        $where[] = ['standard', '=', $param['standard']];
        $where[] = ['cid', '=', $param['cid']];
        $product = $this->productRepository->findWhere($where);
        if($product){
            return ['status'=>'0', 'msg'=> '相同产品已存在'];
        }
        $res = $this->productRepository->save($param);
        if($res){
            return ['status'=>'1', 'msg'=> '添加成功'];
        }else{
            return ['status'=>'0', 'msg'=> '添加失败'];
        }
    }

    public function updateProcess($param, $id)
    {
        $where[] = ['customer_id', '=', $param['customer_id']];
        $where[] = ['name', '=', $param['name']];
        $where[] = ['hscode', '=', $param['hscode']];
        $where[] = ['standard', '=', $param['standard']];
        $where[] = ['cid', '=', $param['cid']];
        $where[] = ['id', '<>', $id];
        $product = $this->productRepository->findWhere($where);
        if($product){
            return ['status'=>'0', 'msg'=> '相同产品已存在'];
        }
        $res = $this->productRepository->update($param, $id);
        if($res){
            return ['status'=>'1', 'msg'=> '修改成功'];
        }else{
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }

    public function update($param, $id)
    {
        $res = $this->productRepository->update($param, $id);
        if($res){
            return ['status'=>'1', 'msg'=> '修改成功'];
        }else{
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }

    public function retrieve($id)
    {
        $drawer = $this->productRepository->find($id);
        if(!$drawer){
            return ['status'=>'0', 'msg'=> '该产品不存在'];
        }else if($drawer->status != 2){
            return ['status'=>'0', 'msg'=> '该产品状态不能取回'];
        }
        $res = $this->productRepository->update(['status'=> 1], $id);
        if($res){
            return ['status'=>'1', 'msg'=> '取回成功'];
        }else{
            return ['status'=>'0', 'msg'=> '取回失败'];
        }
    }

    public function destroy($id)
    {
        $drawer_product = $this->drawerRepository->getDrawerProductByProId($id);
        if(count($drawer_product->toArray()) > 0){
            return ['status'=>'1', 'msg'=> '该产品已被使用，不能删除'];
        }
        $res = $this->productRepository->destroy($id);
        if($res){
            return ['status'=>'1', 'msg'=> '删除成功'];
        }else{
            return ['status'=>'0', 'msg'=> '删除失败'];
        }
    }

    public function getProductInfo($id)
    {
        return $this->productRepository->getProWithRelation($id);
    }
}

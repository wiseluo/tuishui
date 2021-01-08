<?php

namespace App\Services\Admin;

use App\Repositories\DrawerRepository;
use App\Repositories\ProductRepository;
use App\Repositories\DrawerProductRepository;
use Illuminate\Support\Facades\DB;

class DrawerProductService
{
    public function __construct(DrawerRepository $drawerRepository, ProductRepository $productRepository, DrawerProductRepository $drawerProductRepository)
    {
        $this->drawerRepository = $drawerRepository;
        $this->productRepository = $productRepository;
        $this->drawerProductRepository = $drawerProductRepository;
    }

    //产品新增及关联开票人
    public function drawerProductRelateService($param)
    {
        $drawer = $this->drawerRepository->find($param['drawer_id']);
        $product = $this->productRepository->findWhere(['customer_id'=>$drawer['customer_id'], 'name'=>$param['name'], 'hscode'=>$param['hscode'], 'standard'=>$param['standard']]);
        DB::beginTransaction();
        if($product) {
            $product_id = $product['id'];
        }else{
            $product_data = [
                'customer_id' => $drawer['customer_id'],
                'name' => $param['name'],
                'en_name' => $param['en_name'],
                'hscode' => $param['hscode'],
                'standard' => $param['standard'],
                'measure_unit' => $param['measure_unit'],
                'tax_refund_rate' => $param['tax_refund_rate'],
            ];
            $product_id = $this->productRepository->save($product_data);
            if($product_id == null) {
                DB::rollback();
                return ['status'=> 0, 'msg'=> '添加产品失败'];
            }
        }
        $drawer_product = $this->drawerProductRepository->findWhere(['product_id'=> $product_id, 'drawer_id'=> $param['drawer_id']]);
        if($drawer_product == null) {
            $drawer_product_id = $this->drawerProductRepository->save(['product_id'=> $product_id, 'drawer_id'=> $param['drawer_id']]);
            if($drawer_product_id == null) {
                DB::rollback();
                return ['status'=> 0, 'msg'=> '添加关联失败'];
            }
        }else{
            $drawer_product_id = $drawer_product['id'];
        }
        DB::commit();
        return ['status'=> 1, 'msg'=> '添加关联成功', 'data'=> $drawer_product_id];
    }
}

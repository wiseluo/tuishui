<?php

namespace App\Services\Member;

use App\Repositories\ProductRepository;
use App\Repositories\ProductlogRepository;
use App\Repositories\DrawerRepository;
use App\Repositories\NotificationRepository;
use DB;

class ProductService
{
    protected $productRepository;
    protected $productlogRepository;
    protected $product_value = [
        'customer_id' => '客户',
        'name' => '产品名称',
        'en_name' => '产品英文名称',
        'hscode' => 'HSCode',
        'standard' => '规格',
        'unit' => '法定单位',
        'number' => '货号',
        'tax_refund_rate' => '退税率(%)',
        'remark' => '备注',
        'measure_unit' => '计量单位',
        'picture' => '产品图片',
        'customs_img' => '历史报关单',
        'appearance_img' => '产品整体外观图',
        'brand_img' => '品牌图',
        'pack_img' => '产品内包装图',
        'other_img' => '其他',
    ];

    public function __construct(ProductRepository $productRepository, ProductlogRepository $productlogRepository, DrawerRepository $drawerRepository, NotificationRepository $notificationRepository)
    {
        $this->productRepository = $productRepository;
        $this->productlogRepository = $productlogRepository;
        $this->drawerRepository = $drawerRepository;
        $this->notificationRepository = $notificationRepository;
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
            $item['customer_name'] = $value['customer']['name'];
            $item['name'] = $value['name'];
            $item['en_name'] = $value['en_name'];
            $item['hscode'] = $value['hscode'];
            $item['standard'] = $value['standard'];
            $item['tax_refund_rate'] = $value['tax_refund_rate'];
            $item['number'] = $value['number'];
            $item['measure_unit'] = $value['measure_unit_data']['name'];
            $item['status'] = $value['status'];
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
    
    public function approve($param, $id)
    {
        $res = $this->productRepository->update($param, $id);
        if($res){
            //消息提醒
            $product = $this->productRepository->find($id);
            $data = [];
            $data['cid'] = $param['cid'];
            $data['receiver_id'] = $product->created_user_id;
            $data['content'] = '您提交的【产品管理】'. $product->name;
            if($param['status'] == 3){
                $data['content'] .= '已审核通过';
            }else{
                $data['content'] .= '被审核拒绝';
            }
            $this->notificationRepository->save($data);
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
    
    // public function update($params, $id, $ip)
    // {
    //     $pro_old = $this->productRepository->select($id)->toArray();
    //     DB::beginTransaction();
    //     $pro_res = $this->productRepository->update($params, $id);
    //     if($pro_res){
    //         $pro_new = $this->productRepository->select($id)->toArray();
    //         $params['product_id'] = $id;
    //         $params['content'] = $this->product_diff_update($pro_old, $pro_new);
    //         $params['ip'] = $ip;
    //         $log_res = $this->productlogRepository->save($params);
    //         if($log_res){
    //             DB::commit();
    //             return 1;
    //         }else{
    //             DB::rollback();
    //             return 0;
    //         }
    //     }else{
    //         DB::rollback();
    //         return 0;
    //     }
    // }

    //修改了哪些内容
    public function product_diff_update($pro_old, $pro_new)
    {
        $content = '';
        $diff = array_diff_assoc($pro_old, $pro_new);
        foreach($diff as $k => $v){
            if(array_key_exists($k, $this->product_value)){
                $content .= $this->product_value[$k].':原值为'.$pro_old[$k].',修改为'.$pro_new[$k].';';
            }
        }
        return $content == '' ? '未修改任何内容' : $content;
    }

}

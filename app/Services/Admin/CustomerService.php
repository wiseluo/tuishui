<?php

namespace App\Services\Admin;

use App\Repositories\CustomerRepository;
use App\Repositories\DrawerRepository;

class CustomerService
{
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository, DrawerRepository $drawerRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->drawerRepository = $drawerRepository;
    }

    public function renderStatus()
    {
        return $this->customerRepository->renderStatus();
    }

    public function customerIndex($param)
    {
        $list = $this->customerRepository->customerIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['number'] = $value['number'];
            $item['created_at'] = $value['created_at'];
            $item['linkman'] = $value['linkman'];
            $item['address'] = $value['address'];
            $item['telephone'] = $value['telephone'];
            $item['salesman'] = $value['salesman'];
            $item['created_user_name'] = $value['created_user_name'];
            $item['status'] = $value['status'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function searchCustomerByDrawerName($param)
    {
        $list = $this->drawerRepository->searchDrawerByNameRepository($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['customer']['id'];
            $item['company'] = $value['company'];
            $item['tax_id'] = $value['tax_id'];
            $item['telephone'] = $value['telephone'];
            $item['customer_name'] = $value['customer']['name'];
            return $item;
        }, $list);
        return $data;
    }
    
    public function find($id)
    {
        return $this->customerRepository->find($id);
    }

    public function save($data)
    {
        $res = $this->customerRepository->save($data);
        if($res){
            return ['status'=>'1', 'msg'=> '添加成功'];
        }else{
            return ['status'=>'0', 'msg'=> '添加失败'];
        }
    }

    public function update($data, $id)
    {
        $res = $this->customerRepository->update($data, $id);
        if($res){
            return ['status'=>'1', 'msg'=> '修改成功'];
        }else{
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }
    
    public function retrieve($id)
    {
        $drawer = $this->customerRepository->find($id);
        if(!$drawer){
            return ['status'=>'0', 'msg'=> '该客户不存在'];
        }else if($drawer->status != 2){
            return ['status'=>'0', 'msg'=> '该客户状态不能取回'];
        }
        $res = $this->customerRepository->update(['status'=> 1], $id);
        if($res){
            return ['status'=>'1', 'msg'=> '取回成功'];
        }else{
            return ['status'=>'0', 'msg'=> '取回失败'];
        }
    }

    public function destroy($id)
    {
        $res = $this->customerRepository->destroy($id);
        if($res){
            return ['status'=>'1', 'msg'=> '删除成功'];
        }else{
            return ['status'=>'0', 'msg'=> '删除失败'];
        }
    }
}

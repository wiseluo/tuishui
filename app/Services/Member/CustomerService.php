<?php

namespace App\Services\Member;

use App\Repositories\CustomerRepository;
use App\Repositories\CustomerlogRepository;
use DB;

class CustomerService
{
    protected $customerRepository;
    protected $customerlogRepository;
    protected $customer_value = [
        'address' => '公司地址',
        'service_rate' => '服务费率(%)',
        'receiver' => '收款人',
        'deposit_bank' => '开户银行',
        'bank_account' => '银行账号',
        'picture_lic' => '证照',
        'picture_pact' => '合同',
        'picture_other' => '其他',
        'line_credit' => '授信额度',
        'email' => '邮箱',
        'usc_code' => '社会统一信用代码',
        'reg_capital' => '注册资本',
        'set_date' => '成立日期',
        'legal_name' => '法人姓名',
        'legal_num' => '法人身份证号',
        'bill_base' => '计费基数',
        'status' => '状态',
    ];

    public function __construct(CustomerRepository $customerRepository, CustomerlogRepository $customerlogRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->customerlogRepository = $customerlogRepository;
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
    
    public function find($id)
    {
        return $this->customerRepository->find($id);
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

    public function updateWithLog($params, $id, $ip)
    {
        $cus_old = $this->customerRepository->find($id)->toArray();
        DB::beginTransaction();
        $res = $this->customerRepository->update($params, $id);
        if($res){
            $cus_new = $this->customerRepository->find($id)->toArray();
            $params['customer_id'] = $id;
            $params['content'] = $this->customer_diff_update($cus_old, $cus_new);
            $params['ip'] = $ip;
            $log_res = $this->customerlogRepository->save($params);
            if($log_res){
                DB::commit();
                return ['status'=>'1', 'msg'=> '修改成功'];
            }else{
                DB::rollback();
                return ['status'=>'0', 'msg'=> '修改失败'];
            }
        }else{
            DB::rollback();
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }

    //修改了哪些内容
    public function customer_diff_update($old, $new)
    {
        $content = '';
        $diff = array_diff_assoc($old, $new);
        foreach($diff as $k => $v){
            if(array_key_exists($k, $this->customer_value)){
                $content .= $this->customer_value[$k].':原值为'.$old[$k].',修改为'.$new[$k].';';
            }
        }
        return $content == '' ? '未修改任何内容' : $content;
    }

}

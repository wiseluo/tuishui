<?php

namespace App\Services\Admin;

use App\Repositories\RemitteeRepository;

class RemitteeService
{
    public function __construct(RemitteeRepository $remitteeRepository)
    {
        $this->remitteeRepository = $remitteeRepository;
    }

    public function remitteeIndexService($param)
    {
        $list = $this->remitteeRepository->remitteeIndex($param);
        //dd($list);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['tag'] = $value['tag'];
            $item['bank'] = $value['bank'];
            $item['number'] = $value['number'];
            $item['remit_type_str'] = $value['remit_type_str'];
            $item['created_user_name'] = $value['created_user_name'];
            $item['created_at'] = $value['created_at'];
            $item['status'] = $value['status'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function findService($id)
    {
        return $this->remitteeRepository->find($id);
    }

    public function getRemitteeTypeService()
    {
        return $this->remitteeRepository->getRemitteeType();
    }

    public function save($param)
    {
        $res = $this->remitteeRepository->save($param);
        if($res){
            return ['status'=>'1', 'msg'=> '添加成功'];
        }else{
            return ['status'=>'0', 'msg'=> '添加失败'];
        }
    }

    public function update($param, $id)
    {
        $res = $this->remitteeRepository->update($param, $id);
        if($res){
            return ['status'=>'1', 'msg'=> '修改成功'];
        }else{
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }
}

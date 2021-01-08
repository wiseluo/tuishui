<?php

namespace App\Services\Admin;

use App\Repositories\CompanyRepository;

class CompanyService
{
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function companyIndex($param)
    {
        $list = $this->companyRepository->companyIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['customs_code'] = $value['customs_code'];
            $item['tax_id'] = $value['tax_id'];
            $item['telephone'] = $value['telephone'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function find($id)
    {
        return $this->companyRepository->find($id);
    }

    public function save($param)
    {
        $res = $this->companyRepository->save($param);
        if($res){
            return ['status'=> 1, 'msg'=> '添加成功'];
        }else{
            return ['status'=> 0, 'msg'=> '添加失败'];
        }
    }

    public function update($param, $id)
    {
        $res = $this->companyRepository->update($param, $id);
        if($res){
            return ['status'=> 1, 'msg'=> '修改成功'];
        }else{
            return ['status'=> 0, 'msg'=> '修改失败'];
        }
    }

    public function delete($id)
    {
        $res = $this->companyRepository->delete($id);
        if($res){
            return ['status'=> 1, 'msg'=> '删除成功'];
        }else{
            return ['status'=> 0, 'msg'=> '删除失败'];
        }
    }
}

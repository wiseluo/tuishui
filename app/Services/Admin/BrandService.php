<?php

namespace App\Services\Admin;

use App\Repositories\BrandRepository;

class BrandService
{
    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function renderStatus()
    {
        return $this->brandRepository->renderStatus();
    }

    public function brandIndex($param)
    {
        $list = $this->brandRepository->brandIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['type_str'] = $value['type_str'];
            $item['classify_str'] = $value['classify_str'];
            $item['created_user_name'] = $value['created_user_name'];
            $item['status'] = $value['status'];
            $item['status_str'] = $value['status_str'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function find($id)
    {
        return $this->brandRepository->findWithRelation($id);
    }

    public function save($param)
    {
        $res = $this->brandRepository->save($param);
        if($res){
            return ['status'=> 1, 'msg'=> '添加成功'];
        }else{
            return ['status'=> 0, 'msg'=> '添加失败'];
        }
    }

    public function update($param, $id)
    {
        $res = $this->brandRepository->update($param, $id);
        if($res){
            return ['status'=> 1, 'msg'=> '修改成功'];
        }else{
            return ['status'=> 0, 'msg'=> '修改失败'];
        }
    }

    public function destroy($id)
    {
        $res = $this->brandRepository->destroy($id);
        if($res){
            return ['status'=> 1, 'msg'=> '删除成功'];
        }else{
            return ['status'=> 0, 'msg'=> '删除失败'];
        }
    }

}

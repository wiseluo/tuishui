<?php

namespace App\Services\Admin;

use App\Repositories\TraderRepository;

class TraderService
{
    public function __construct(TraderRepository $traderRepository)
    {
        $this->traderRepository = $traderRepository;
    }

    public function traderIndex($param)
    {
        $list = $this->traderRepository->traderIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['country_en'] = $value['country']['country_en'];
            $item['address'] = $value['address'];
            $item['email'] = $value['email'];
            $item['cellphone'] = $value['cellphone'];
            $item['url'] = $value['url'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function find($id)
    {
        return $this->traderRepository->find($id);
    }

    public function save($param)
    {
        $res = $this->traderRepository->save($param);
        if($res){
            return ['status'=>'1', 'msg'=> '添加成功'];
        }else{
            return ['status'=>'0', 'msg'=> '添加失败'];
        }
    }

    public function update($param, $id)
    {
        $res = $this->traderRepository->update($param, $id);
        if($res){
            return ['status'=>'1', 'msg'=> '修改成功'];
        }else{
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }

    public function delete($id)
    {
        $res = $this->traderRepository->delete($id);
        if($res){
            return ['status'=>'1', 'msg'=> '删除成功'];
        }else{
            return ['status'=>'0', 'msg'=> '删除失败'];
        }
    }

}

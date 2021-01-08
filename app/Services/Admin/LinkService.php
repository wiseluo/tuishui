<?php

namespace App\Services\Admin;

use App\Repositories\LinkRepository;

class LinkService
{
    public function __construct(LinkRepository $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    public function linkIndex($param)
    {
        $list = $this->linkRepository->linkIndex($param);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['phone'] = $value['phone'];
            $item['created_user_name'] = $value['created_user_name'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function find($id)
    {
        return $this->linkRepository->find($id);
    }

    public function save($param)
    {
        $res = $this->linkRepository->save($param);
        if($res){
            return ['status'=> 1, 'msg'=> '添加成功'];
        }else{
            return ['status'=> 0, 'msg'=> '添加失败'];
        }
    }

    public function update($param, $id)
    {
        $res = $this->linkRepository->update($param, $id);
        if($res){
            return ['status'=> 1, 'msg'=> '修改成功'];
        }else{
            return ['status'=> 0, 'msg'=> '修改失败'];
        }
    }

    public function delete($id)
    {
        $res = $this->linkRepository->delete($id);
        if($res){
            return ['status'=> 1, 'msg'=> '删除成功'];
        }else{
            return ['status'=> 0, 'msg'=> '删除失败'];
        }
    }
}

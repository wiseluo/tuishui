<?php

namespace App\Services\Member;

use App\Repositories\BrandRepository;
use App\Repositories\NotificationRepository;

class BrandService
{
    public function __construct(BrandRepository $brandRepository, NotificationRepository $notificationRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->notificationRepository = $notificationRepository;
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
            return ['status'=>'1', 'msg'=> '添加成功'];
        }else{
            return ['status'=>'0', 'msg'=> '添加失败'];
        }
    }

    public function update($param, $id)
    {
        $res = $this->brandRepository->update($param, $id);
        if($res){
            return ['status'=>'1', 'msg'=> '修改成功'];
        }else{
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }

    public function approve($param, $id)
    {
        $res = $this->brandRepository->update($param, $id);
        if($res){
            //消息提醒
            $brand = $this->brandRepository->find($id);
            $data = [];
            $data['cid'] = $param['cid'];
            $data['receiver_id'] = $brand->created_user_id;
            $data['content'] = '您提交的【品牌管理】'. $brand->name;
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

    public function destroy($id)
    {
        $res = $this->brandRepository->destroy($id);
        if($res){
            return ['status'=>'1', 'msg'=> '删除成功'];
        }else{
            return ['status'=>'0', 'msg'=> '删除失败'];
        }
    }

    public function getBrandList($param)
    {
        $where[] = ['status', '=', 3];
        return $this->brandRepository->getBrandList($where, $param);
    }

}

<?php

namespace App\Services\Api;

use App\Repositories\Api\NotificationRepository;

class NotificationService
{
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function noticeIndexService($param, $where)
    {
        $list = $this->notificationRepository->noticeIndex($param, $where);
        //dd($list);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['content'] = $value['content'];
            $item['created_at'] = $value['created_at'];
            $item['status'] = $value['status'];
            $item['status_str'] = $value['status_str'];
            $item['readed'] = $value['readed'];
            $item['readed_str'] = $value['readed_str'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function setReadedService($param)
    {
        $res = $this->notificationRepository->updateByWhere(['readed'=> 1], ['receiver_id'=> $param['receiver_id'], 'cid'=> $param['cid']]);
        if($res){
            return ['status'=>'1', 'msg'=> '修改成功'];
        }else{
            return ['status'=>'0', 'msg'=> '修改失败'];
        }
    }
}

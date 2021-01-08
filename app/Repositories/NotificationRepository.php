<?php

namespace App\Repositories;

use App\Notification;

class NotificationRepository
{
    public function noticeIndex($param)
    {
        $where = [];
        if($param['cid'] !== ''){
            $where[] = ['cid', '=', $param['cid']];
        }
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Notification::where($where)
            //->with(['sender', 'receiver'])
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    public function save($param)
    {
        $notice = new Notification($param);
        $notice->save();
        return $notice->id;
    }
}
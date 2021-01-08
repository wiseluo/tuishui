<?php

namespace App\Repositories\Api;

use App\Notification;

class NotificationRepository
{
    public function noticeIndex($param, $where)
    {
        $keyword = isset($param['keyword']) ? $param['keyword'] : '';
        $pageSize = isset($param['pageSize']) ? $param['pageSize'] : 10;
        return Notification::where($where)
            //->with(['sender', 'receiver'])
            ->orderBy('id', 'desc')
            //->toSql();
            ->paginate($pageSize)
            ->toArray();
    }

    public function updateByWhere($param, $where)
    {
        return Notification::where($where)->update($param);
    }
}
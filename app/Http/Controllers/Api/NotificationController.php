<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Api\NotificationService;

class NotificationController extends BaseController
{
    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function noticeIndex(Request $request)
    {
        $param = $request->input();
        $where = [];
        $where['cid'] = $this->getUserCid();
        $where['receiver_id'] = $this->getUserid();
        $where['readed'] = 0;
        $data = $this->notificationService->noticeIndexService($param, $where);
        return response()->json(['code'=>'200', 'data'=> $data]);
    }

    public function noticeReaded(Request $request)
    {
        $param['cid'] = $this->getUserCid();
        $param['receiver_id'] = $this->getUserid();
        $res = $this->notificationService->setReadedService($param);
        if($res['status']){
            return response()->json(['code'=>'200', 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=>'401', 'msg'=> $res['msg']]);
        }
    }
}

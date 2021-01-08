<?php

namespace App\Http\Controllers\Erp;

use Illuminate\Http\Request;
use App\Services\Erp\TransportService;
use App\Http\Requests\Erp\TransportPostRequest;

class TransportController extends BaseController
{
    public function __construct(TransportService $transportService)
    {
        parent::__construct();
        $this->transportService = $transportService;
    }

    public function orderList(TransportPostRequest $request)
    {
        $list = $this->transportService->orderList($request->input());
        return response()->json(['code'=> 200, 'data'=> $list]);

    }

    public function transportComplete(TransportPostRequest $request)
    {
        $transport = $request->input('transport');
        $order = $request->input('order');

        $res = $this->transportService->transportAddService($transport, $order);
        if($res['status']){
            return response()->json(['code'=>200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=>400, 'msg'=> $res['msg']]);
        }
        
    }
}

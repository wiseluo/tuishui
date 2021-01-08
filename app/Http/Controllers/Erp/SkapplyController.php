<?php

namespace App\Http\Controllers\Erp;

use Illuminate\Http\Request;
use App\Services\Erp\SkapplyService;
use App\Http\Requests\Erp\SkapplyPostRequest;

class SkapplyController extends BaseController
{
    public function __construct(SkapplyService $skapplyService)
    {
        parent::__construct();
        $this->skapplyService = $skapplyService;
    }

    public function receiptReturn(SkapplyPostRequest $request)
    {
        $res = $this->skapplyService->receiptReturnService($request->input());
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 400, 'msg'=> $res['msg']]);
        }
    }

    public function receiptExchange(SkapplyPostRequest $request)
    {
        $res = $this->skapplyService->receiptExchangeService($request->input());
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 400, 'msg'=> $res['msg']]);
        }
    }

}

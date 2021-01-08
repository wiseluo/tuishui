<?php

namespace App\Http\Controllers\Erp;

use Illuminate\Http\Request;
use App\Services\Erp\YwapplyService;
use App\Http\Requests\Erp\YwapplyPostRequest;

class YwapplyController extends BaseController
{
    public function __construct(YwapplyService $ywapplyService)
    {
        parent::__construct();
        $this->ywapplyService = $ywapplyService;
    }

    public function payReturn(YwapplyPostRequest $request)
    {
        $res = $this->ywapplyService->payReturnService($request->input());
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 400, 'msg'=> $res['msg']]);
        }
    }

    public function payReturnTemp(Request $request)
    {
        $res = $this->ywapplyService->payReturnTempService($request->input());
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 400, 'msg'=> $res['msg']]);
        }
    }
}

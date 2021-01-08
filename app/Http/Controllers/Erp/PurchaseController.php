<?php

namespace App\Http\Controllers\Erp;

use Illuminate\Http\Request;
use App\Services\Erp\PurchaseService;
use App\Http\Requests\Erp\PurchasePostRequest;

class PurchaseController extends BaseController
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        parent::__construct();
        $this->purchaseService = $purchaseService;
    }

    public function purchaseThrowTax(PurchasePostRequest $request)
    {
        $res = $this->purchaseService->savePurchaseFromErp($request->input());
        if($res){
            return json_encode(array('code'=> 200, 'msg'=> '成功'), JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array('code'=> 400, 'msg'=> '失败'), JSON_UNESCAPED_UNICODE);
        }
    }

}

<?php

namespace App\Http\Controllers\Erp;

use Illuminate\Http\Request;
use App\Http\Requests\Erp\TaxapiPostRequest;
use App\Services\Erp\TaxapiService;
use App\Order;

class TaxapiController extends BaseController
{
    public function __construct(TaxapiService $taxapiService)
    {
        $this->taxapiService = $taxapiService;
    }

    //获取未开完发票的订单开票产品列表
    public function taxUninvoiceOrderProList(TaxapiPostRequest $request)
    {
        $param = $request->input();
        $list = $this->taxapiService->uninvoiceOrderProListService($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }

    //根据订单id，开票人id获取订单产品详情
    public function taxOrderWithProDetail(TaxapiPostRequest $request)
    {
        $param = $request->input();
        $data = $this->taxapiService->orderWithProDetailService($param);
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    //发票审核完成同步到退税
    public function taxInvoiceComplete(TaxapiPostRequest $request)
    {
        $invoice = $request->input('invoice');
        $product = $request->input('product');
        $res = $this->taxapiService->invoiceCompleteService($invoice, $product);
        if($res['status']){
            return response()->json(['code'=>200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=>400, 'msg'=> $res['msg']]);
        }
    }

    //申报流程开始时修改退税发票状态为已申报
    public function taxFilingStart(TaxapiPostRequest $request)
    {
        $id = $request->input('id');
        $invoice = $request->input('invoice');
        $res = $this->taxapiService->filingStartService($id, $invoice);
        if($res['status']){
            return response()->json(['code'=>200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=>400, 'msg'=> $res['msg']]);
        }
    }

    //申报审核完成同步到退税
    public function taxFilingComplete(TaxapiPostRequest $request)
    {
        $filing = $request->input('filing');
        $invoice = $request->input('invoice');
        $res = $this->taxapiService->filingCompleteService($filing, $invoice);
        if($res['status']){
            return response()->json(['code'=>200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=>400, 'msg'=> $res['msg']]);
        }
    }
}

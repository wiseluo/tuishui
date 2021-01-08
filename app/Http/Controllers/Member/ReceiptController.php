<?php

namespace App\Http\Controllers\Member;

use App\Repositories\DataRepository;
use App\Repositories\OrderRepository;
use App\Http\Requests\Member\ReceiptPostRequest;
use App\Services\Member\ReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function __construct(ReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['cid'] = $this->getUserCid();
            $list = $this->receiptService->receiptIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('member.receipt.index', $this->receiptService->renderStatus());
    }

    public function read(DataRepository $dataRepository, $type = 'read', $id = 0)
    {
        $currency_id = $dataRepository->getTypesByFatherId(5);
        $view = view('member.receipt.recdetail', compact('currency_id'));
        if ($id) {
            $receipt = $this->receiptService->find($id);
            $view->with('receipt', $receipt);
        }
        return $this->disable($view);
    }

    public function save(ReceiptPostRequest $request)
    {
        $param = $request->input();
        $param['cid'] = $this->getUserCid();
        $param['status'] = 2;
        $res = $this->receiptService->save($param);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> '添加成功']);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function update(ReceiptPostRequest $request, $id)
    {
        $param = $request->input();
        $param['status'] = 2;
        $res = $this->receiptService->update($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> '修改成功']);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function exchangeSettlement(ReceiptPostRequest $request, $id)
    {
        $res = $this->receiptService->exchangeService($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> '结汇成功']);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function number($id)
    {
        $receipt = $this->receiptService->find($id);
        return view('member.receipt.recdetail_number', compact('receipt'));
    }

    public function order(Request $request, $id, $flag)
    {
        if ($flag) {
            $receipt = $this->receiptService->findWithRelateService($id);
            return response()->json(['code'=>200, 'data'=> $receipt]);
        } else {
            $param['pageSize'] = $request->input('pageSize', 15);
            $param['customer_id'] = $this->receiptService->find($id)->customer_id;
            $list = $this->receiptService->getUnbindReceiptOrderService($param);
            return response()->json(['code'=>200, 'data'=> $list]);
        }
    }

    public function binding(ReceiptPostRequest $request, $id)
    {
        $param = $request->input();
        $res = $this->receiptService->binding($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else {
            return response()->json(['code' => 401, 'msg' => $res['msg']]);
        }
    }

    public function unbinding(OrderRepository $orderRepository, Request $request, $id)
    {
        $param = $request->input();
        $res = $this->receiptService->unbinding($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else {
            return response()->json(['code' => 401, 'msg' => $res['msg']]);
        }
    }
}

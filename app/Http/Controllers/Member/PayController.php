<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Requests\Member\PayPostRequest;
use App\Services\Member\PayService;

class PayController extends Controller
{
    protected $payService;

    public function __construct(PayService $payService)
    {
        $this->payService = $payService;
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $status)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['status'] = $status;
            $param['cid'] = $this->getUserCid();
            $list = $this->payService->payIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        $renderStatus = $this->payService->renderStatus();
        return view('member.pay.index', $renderStatus);
    }

    public function read($type = 'read', $id = 0)
    {
        $cid = $this->getUserCid();
        $company = $this->payService->getCurCompany($cid);
        $view = view('member.pay.paydetail', ['type' => $this->payService->payType(), 'company'=> $company]);
        if ($id) {
            $view->with('pay', $this->payService->find($id));
        }
        return $this->disable($view);
    }
    
    public function save(PayPostRequest $request)
    {
        $param = $request->input();
        $param['cid'] = $this->getUserCid();
        $res = $this->payService->save($param);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> '添加成功']);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }
    
    public function update(PayPostRequest $request, $id)
    {
        $param = $request->input();
        $param['cid'] = $this->getUserCid();
        $res = $this->payService->update($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> '修改成功']);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function approve(Request $request, $id)
    {
        $param = $request->input();
        $res = $this->payService->approve($param, $id);
        return response()->json(['code'=> 200, 'msg'=> '操作成功']);
    }

    public function relateOrder(PayPostRequest $request, $id)
    {
        if($request->isMethod('post')){
            $res = $this->payService->relateOrder($request->input(), $id);
            if($res){
                return response()->json(['code'=> 200, 'msg'=> '关联成功']);
            }else{
                abort(403, '关联失败', ['msg'=> json_encode('关联失败')]);
            }
        }
        $view = view('member.pay.relateOrder');
        return $view->with('pay', $this->payService->find($id));
    }

    public function relateReceipt(PayPostRequest $request, $id)
    {
        if($request->isMethod('post')){
            $res = $this->payService->relateReceipt($request->input(), $id);
            if($res){
                return response()->json(['code'=> 200, 'msg'=> '关联成功']);
            }else{
                return response()->json(['code'=> 403, 'data'=> '关联失败']);
            }
        }
        $view = view('member.pay.relateReceipt');
        return $view->with('pay', $this->payService->find($id));
    }
    
    public function payOrder(PayPostRequest $request)
    {
        $param = $request->input();
        $param['cid'] = $this->getUserCid();
        $list = $this->payService->payOrderList($param);
        return response()->json(['code'=> 200, 'data'=> $list]);
    }

    public function payReceipt(PayPostRequest $request)
    {
        $param = $request->input();
        $param['cid'] = $this->getUserCid();
        $list = $this->payService->payReceiptList($param);
        return response()->json(['code'=> 200, 'data'=> $list]);
    }

    public function payExport(Request $request, PayExport $payExport)
    {
        $param = $request->input();
        $param['cid'] = $this->getUserCid();
        return $payExport->payExport($param);
    }

}

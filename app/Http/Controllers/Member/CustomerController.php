<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Requests\Member\CustomerPostRequest;
use App\Services\Member\CustomerService;

class CustomerController extends Controller
{
    public function __construct(CustomerService $customerService)
    {
        parent::__construct();
        $this->customerService = $customerService;
    }

    public function index(Request $request, $status = 0)
    {
        if ($request->ajax()) {
            $param['keyword'] = $request->input('keyword', '');
            $param['status'] = $status;
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->customerService->customerIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('member.customer.index', $this->customerService->renderStatus());
    }

    public function update(CustomerPostRequest $request, $id)
    {
        $params = $request->input();
        $ip = $request->getClientIp();
        $res = $this->customerService->updateWithLog($params, $id, $ip);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    //修改已完成的
    public function updateDone(CustomerPostRequest $request, $id)
    {
        $param = $request->input();
        $res = $this->customerService->update($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function approve(Request $request, $id)
    {
        $param = $request->input();
        $res = $this->customerService->update($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function retrieve($id)
    {
        $res = $this->customerService->retrieve($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function read($type = 'read', $id = 0)
    {
        $view = view('member.customer.cusdetail');
        if ($id) {
            $customer = $this->customerService->find($id);
            $view->with('customer', $customer);
        }
        if($type == 'update_done' || $type == 'save' || $type == 'update'){
            $view->with('updateDoneDisabled', false); //用于已审核后修改某些属性
        }else{
            $view->with('updateDoneDisabled', 'disabled');
        }
        return $view->with('disabled', in_array(request()->route('type'), ['read', 'approve', 'update_done']) ? 'disabled' : false);
    }
    
    public function customerChoose(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('member.customer.chooseCustomer');
        }else if($action == 'data'){
            $param = $request->input();
            $param['status'] = 3;
            $param['cid'] = $this->getUserCid();
            $data = $this->customerService->customerIndex($param);
            return response()->json(['code'=>200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }

}

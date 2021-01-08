<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\RegionService;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerPostRequest;
use Curl\Curl;
use App\Services\Admin\CustomerService;
use App\Exports\Admin\CustomerExport;

class CustomerController extends Controller
{
    public function __construct(CustomerService $customerService,RegionService $regionService)
    {
        parent::__construct();
        $this->customerService = $customerService;
        $this->regionService=$regionService;
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
        return view('admin.customer.index', $this->customerService->renderStatus());
    }

    public function save(CustomerPostRequest $request)
    {
        $res = $this->customerService->save(array_filter($request->input()));
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function delete($id)
    {
        $res = $this->customerService->destroy($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function update(CustomerPostRequest $request, $id)
    {
        $res = $this->customerService->update($request->input(), $id);
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
        $res = $this->customerService->update($request->input(), $id);
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
    
    public function read(Curl $curl, $type = 'read', $id = 0)
    {
//	    $province=$this->regionService->getProvince(1);
        //去erp获取员工列表，cs001账号没有token
        $post_data = array(
            'token'=> session('center_token')
        );
        $res = $curl->get(config('app.erpapp_url'). '/staffs', $post_data)->response;
        $result = json_decode($res);
        $salesman = [];
        if($result && $result->code == 200){
            array_map(function($v)use(&$salesman){
                $salesman[$v->u_name] = $v->u_truename;
            }, $result->data);
        }
        $view = view('admin.customer.cusdetail', ['u_name' =>  $salesman]);

        if ($id) {
            $customer = $this->customerService->find($id);
            $view->with(['customer'=>$customer]);
//            $customer->province_code?$province=$this->regionService->getRegionByCode($customer->province_code):$province='';
//	        $customer->city_code?$city=$this->regionService->getRegionByCode($customer->city_code):$city='';
//	        $customer->district_code?$district=$this->regionService->getRegionByCode($customer->district_code):$district='';
//            $view->with(['customer'=>$customer,'city'=>$city,'district'=>$district]);
        }
//        $view->with('addr',$province);
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
            $outsideUser = $this->isOutsideUser();
            if($outsideUser){
                return view('admin.customer.chooseCustomerByDrawer');
            }else{
                return view('admin.customer.chooseCustomer');
            }
        }else if($action == 'data'){
            $param = $request->input();
            $outsideUser = $this->isOutsideUser();
            if($outsideUser){
                $param['keyword'] = $request->input('keyword', '');
                $data = $this->customerService->searchCustomerByDrawerName($param);
            }else{
                $param['status'] = 3;
                $param['userlimit'] = $this->getUserLimit();
                $data = $this->customerService->customerIndex($param);
            }
            return response()->json(['code'=>200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }

    public function customerExport(Request $request, CustomerExport $customerExport)
    {
        $param = $request->input();
        $param['status'] = 3;
        $param['userlimit'] = $this->getUserLimit();
        return $customerExport->customerListExport($param);
    }
}

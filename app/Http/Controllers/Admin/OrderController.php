<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\OrderPostRequest;
use App\Exports\Admin\OrderExport;
use App\Services\Admin\OrderService;
use App\Services\Admin\DrawerProductService;
use Curl\Curl;

class OrderController extends Controller
{
    public function __construct(OrderService $orderService, DrawerProductService $drawerProductService)
    {
        parent::__construct();
        $this->orderService = $orderService;
        $this->drawerProductService = $drawerProductService;
    }

    public function index(Request $request, $status = 0)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['status'] = $status;
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->orderService->orderIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        $returnData = $this->orderService->renderStatus();
        $returnData['outsideUser'] = $this->isOutsideUser();
        return view('admin.order.index', $returnData);
    }
    //草稿添加
    public function draftSave(OrderPostRequest $request)
    {
        $res = $this->orderService->saveWithPro($request->except('pro'), $request->input('pro'));
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }
    //草稿修改
    public function draftUpdate(OrderPostRequest $request, $id)
    {
        $res = $this->orderService->updateWithPro($request->except('pro'), $id, $request->input('pro'));
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }
    //提交添加
    public function save(OrderPostRequest $request)
    {
        $res = $this->orderService->saveWithPro($request->except('pro'), $request->input('pro'));
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }
    //提交修改
    public function update(OrderPostRequest $request, $id)
    {
        $res = $this->orderService->updateWithPro($request->except('pro'), $id, $request->input('pro'));
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function approve(Request $request, $id) //审核
    {
        $param = $request->input();
        $res = $this->orderService->approve($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function retrieve($id)
    {
        $res = $this->orderService->retrieve($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }
    
    public function read(Request $request, $type = 'read', $id = 0)
    {

        $order_attributes = $this->orderService->getOrderAttributes();

        $view = view('admin.order.orddetail');
        if ($id) { //查看，修改，审核
            $order = $this->orderService->find($id);
            $logistics = $order->logistics;
            $view->with(compact('order'));
        }else{ //添加
            $logistics = $request->input('logistics');
        }
        $view->with($order_attributes);
        $view->with(compact('logistics'));

        return $this->disable($view);
    }

    public function delete($id)
    {
        $res = $this->orderService->delete($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    //结案
    public function closeCase(Request $request)
    {
        $res = $this->orderService->closeCase($request->input());
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }
    
    public function harbor($id)
    {
        return $this->orderService->harborService($id);
    }

    //订单选择
    public function orderChoose(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('admin.order.chooseOrder');
        }else if($action == 'data'){
            $param = $request->input();
            $param['status'] = 5;
            $param['cid'] = $this->getUserCid();
            $data = $this->orderService->orderIndex($param);
            return response()->json(['code'=> 200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }

    //获取业务编号列表
    public function bnotelist(Curl $curl, Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('admin.order.chooseBnoteNum');
        }else if($action == 'data'){
            $post_data = array(
                'token'=> session('center_token'),
                'type'=> 0,
                //'status'=> 1
            );
            if($request->input('page')){
                $post_data['page'] = $request->input('page');
            }
            if($request->input('number')){
                $post_data['number'] = $request->input('number');
            }
            if($request->input('bnote_sealingnum')){
                $post_data['bnote_sealingnum'] = $request->input('bnote_sealingnum');
            }
            $res = $curl->get(config('app.erpapp_url'). '/Tdinquires', $post_data)->response;
            $result = json_decode($res);
            if($result && $result->code == 200){
                return response()->json(['code'=> 200, 'data'=> $result->data, 'token'=> session('center_token')]);
                //return json_encode(array('data'=> $result->data, 'token'=> session('center_token')));
            }else{
                return response()->json(['code'=> 403, 'msg'=> '获取业务编号列表失败']);
            }
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }
    //获取erp托单信息
    public function bnoteinfo(Curl $curl, Request $request, $id)
    {
        $post_data = array(
            'token'=> session('center_token')
        );
        $res = $curl->get(config('app.erpapp_url'). '/Tdinquires/'. $id, $post_data)->response;
        $result = json_decode($res);
        if($result && $result->code == 200){
            return response()->json(['code'=> 200, 'data'=> $result->data]);
            //return json_encode(array('data'=> $result->data));
        }else{
            return response()->json(['code'=> 403, 'msg'=> '获取托单物流信息失败']);
            //abort('403', '获取托单物流信息失败', ['msg' => json_encode('获取托单物流信息失败')]);
        }
    }

    public function orderExport(Request $request, OrderExport $orderExport)
    {
        $param = $request->input();
        $param['keyword'] = $request->input('keyword', '');
        $param['userlimit'] = $this->getUserLimit();
        return $orderExport->orderListExport1($param);
    }

    public function drawerProductRelateView(OrderPostRequest $request)
    {
        return view('admin.order.drawerProductRelate');
    }
    
    public function drawerProductRelate(OrderPostRequest $request)
    {
        $res = $this->drawerProductService->drawerProductRelateService($request->input());
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function customsOrderSave(OrderPostRequest $request)
    {
        $res = $this->orderService->customsOrderSaveService($request->except('pro'), $request->input('pro'));
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function fieldMatchId(OrderPostRequest $request)
    {
        $res = $this->orderService->fieldMatchIdService($request->except('pro'), $request->input('pro'));
        return response()->json(['code'=> 200, 'data'=> $res]);
    }
}

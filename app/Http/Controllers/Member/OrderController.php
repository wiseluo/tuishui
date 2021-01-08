<?php

namespace App\Http\Controllers\Member;

use App\Repositories\CustomerRepository;
use App\Repositories\CountryRepository;
use App\Repositories\PortRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Member\OrderPostRequest;
use App\Services\Member\OrderService;
use App\Services\Member\DataService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
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
        $renderStatus = $this->orderService->renderStatus();
        return view('member.order.index', $renderStatus);
    }
    
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
        $param['cid'] = $this->getUserCid();
        $res = $this->orderService->approve($param, $id);
        if($res['status']){
            return response()->json(['code'=>'200', 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function retrieve($id)
    {
        $res = $this->orderService->retrieve($id);
        if($res['status']){
            return response()->json(['code'=>'200', 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }
    
    public function read(Request $request, CountryRepository $countryRepository, PortRepository $portRepository, $type = 'read', $id = 0)
    {
        $shipment_port = $portRepository->getChinaPort();
        $trade_country = $countryRepository->getCountrylist();
        $aim_country = $countryRepository->getCountrylist();

        $order_attributes = $this->orderService->getOrderAttributes();

        $view = view('member.order.orddetail');
        if ($id) { //查看，修改，审核
            $order = $this->orderService->find($id);
            $logistics = $order->logistics;
            $view->with(compact('order'));
        }else{ //添加
            $logistics = $request->input('logistics');
        }
        $view->with($order_attributes);
        $view->with(compact('shipment_port','trade_country','aim_country','logistics'));

        return $this->disable($view);
    }

    public function delete($id)
    {
        $res = $this->orderService->delete($id);
        if($res['status']){
            return response()->json(['code'=>'200', 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    //结案
    public function closeCase(Request $request)
    {
        $res = $this->orderService->closeCase($request->input());
        if($res['status']){
            return response()->json(['code'=>'200', 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }
    
    public function harbor(CountryRepository $countryRepository, $id)
    {
       return $countryRepository->getCountryDetail($id);
    }

    //订单选择
    public function orderChoose(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('member.order.chooseOrder');
        }else if($action == 'data'){
            $param = $request->input();
            $param['status'] = 5;
            $param['userlimit'] = $this->getUserLimit();
            $data = $this->orderService->orderIndex($param);
            return response()->json(['code'=>200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }
}

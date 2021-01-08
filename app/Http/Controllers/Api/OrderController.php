<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\OrderApiRequest;
use App\Repositories\CountryRepository;
use App\Repositories\PortRepository;
use App\Services\Api\OrderService;

class OrderController extends BaseController
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $param = $request->input();
        $param['status'] = $request->input('status', 0);
        $param['userlimit'] = $this->getUserLimit();
        $list = $this->orderService->orderIndex($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }

    public function show($id)
    {
        $data = $this->orderService->showService($id);
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    public function create(CountryRepository $countryRepository, PortRepository $portRepository)
    {
        $data = $this->orderService->getOrderAttributes();
        $data['shipment_port'] = $portRepository->getChinaPort();
        $data['trade_country'] = $countryRepository->getCountrylist();
        $data['aim_country'] = $countryRepository->getCountrylist();
        return response()->json(['code'=>200, 'data'=> $data]);
    }
    //草稿添加
    public function draftSave(OrderApiRequest $request)
    {
        $param = $request->except('pro');
        $param['customer_id'] = $this->getCustomerid();
        $param['from_type'] = 1;
        $param['company_id'] = $this->getUserCid();
        $param['business'] = 104;
        $pro = $request->input('pro');
        $res = $this->orderService->saveWithPro($param, $pro);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 400, 'msg'=> $res['msg']]);
        }
    }
    //草稿修改
    public function draftUpdate(OrderApiRequest $request, $id)
    {
        $res = $this->orderService->updateWithPro($request->except('pro'), $id, $request->input('pro'));
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 400, 'msg'=> $res['msg']]);
        }
    }

    public function save(OrderApiRequest $request)
    {
        $param = $request->except('pro');
        $param['customer_id'] = $this->getCustomerid();
        $param['from_type'] = 1;
        $param['company_id'] = $this->getUserCid();
        $param['business'] = 104;
        $pro = $request->input('pro');
        $res = $this->orderService->saveWithPro($param, $pro);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 400, 'msg'=> $res['msg']]);
        }
    }

    public function update(OrderApiRequest $request, $id)
    {
        $res = $this->orderService->updateWithPro($request->except('pro'), $id, $request->input('pro'));
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 400, 'msg'=> $res['msg']]);
        }
    }

    public function retrieve($id)
    {
        $res = $this->orderService->retrieve($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 400, 'msg'=> $res['msg']]);
        }
    }
    
    public function delete($id)
    {
        $res = $this->orderService->delete($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 400, 'msg'=> $res['msg']]);
        }
    }

}

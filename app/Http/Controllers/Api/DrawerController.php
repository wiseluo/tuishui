<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\DrawerApiRequest;
use App\Services\Api\DrawerService;

class DrawerController extends BaseController
{
    public function __construct(DrawerService $drawerService)
    {
        parent::__construct();
        $this->drawerService = $drawerService;
    }

    public function index(Request $request)
    {
        $param = $request->input();
        $param['status'] = $request->input('status', 0);
        $where = $this->getBaseWhere();
        $param['userlimit'] = $where;
        $list = $this->drawerService->drawerIndex($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }

    public function show($id)
    {
        $data = $this->drawerService->showService($id);
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    public function delete($id)
    {
        $res = $this->drawerService->destroy($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function update(DrawerApiRequest $request, $id)
    {
        $param = $request->input();
        $res = $this->drawerService->update($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function approve(Request $request, $id)
    {
        $res = $this->drawerService->update($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function retrieve($id)
    {
        $res = $this->drawerService->retrieve($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    //修改已完成的
    public function updateDone(DrawerApiRequest $request, $id)
    {
        $param['tax_rate'] = $request->input('tax_rate');
        $res = $this->drawerService->update($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function relateProducts(DrawerApiRequest $request, $id)
    {
        $res = $this->drawerService->relateProducts($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function drawerProducts(Request $request)
    {
        $param = $request->input();
        $param['customer_id'] = $this->getCustomerid();
        $param['cid'] = $this->getUserCid();
        $data = $this->drawerService->getDrawerProductListByCustomerId($param);
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    //根据开票人产品id 获取开票人产品详情
    public function drawerProductDetail(Request $request, $id)
    {
        $data = $this->drawerService->getDrawerProductDetail($id);
        return response()->json(['code'=>200, 'data'=> $data]);
    }
}

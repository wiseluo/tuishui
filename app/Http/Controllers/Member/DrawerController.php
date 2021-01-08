<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Requests\Member\DrawerPostRequest;
use App\Services\Member\DrawerService;
use App\Exports\Member\DrawerExport;

class DrawerController extends Controller
{
    public function __construct(DrawerService $drawerService)
    {
        parent::__construct();
        $this->drawerService = $drawerService;
    }

    public function index(Request $request, $status = 0)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['status'] = $status;
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->drawerService->drawerIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }

        return view('member.drawer.index', $this->drawerService->renderStatus());
    }
    
    public function save(DrawerPostRequest $request)
    {
        $res = $this->drawerService->save($request->input());
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
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

    public function update(DrawerPostRequest $request, $id)
    {
        $res = $this->drawerService->update($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
        
    }

    public function approve(Request $request, $id)
    {
        $param = $request->input();
        $param['cid'] = $this->getUserCid();
        $res = $this->drawerService->approve($param, $id);
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

    //修改已完成的产品
    public function updateDone(DrawerPostRequest $request, $id)
    {
        $param['tax_rate'] = $request->input('tax_rate');
        $res = $this->drawerService->update($param, $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function read($type = 'read', $id = 0)
    {
        $view = view('member.drawer.dradetail');

        if ($id) {
            $drawer = $this->drawerService->find($id);
            $view->with('drawer', $drawer);
        }
        if($type == 'update_done' || $type == 'save' || $type == 'update'){
            $view->with('updateDoneDisabled', false); //用于修改已完成开票人的某些属性
        }else{
            $view->with('updateDoneDisabled', 'disabled');
        }
        return $view->with('disabled', in_array(request()->route('type'), ['read', 'approve', 'update_done', 'relate_product']) ? 'disabled' : false);
    }

    public function relateProducts(DrawerPostRequest $request, $id)
    {
        $res = $this->drawerService->relateProducts($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function choose(Request $request)
    {
        return view('member.dialogs.drawer');
    }

    public function drawerProducts(Request $request, $id)
    {
        $action = $request->input('action');
        if($action == 'html'){
            $customer_id = $id;
            return view('member.drawer.chooseDrawerProduct', compact('customer_id'));
        }else if($action == 'data'){
            $param = $request->input();
            $param['customer_id'] = $id;
            $param['userlimit'] = $this->getUserLimit();
            $data = $this->drawerService->getDrawerProductListByCustomerId($param);
            return response()->json(['code'=> 200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }
    
    //根据开票人产品id 获取开票人产品详情
    public function drawerProductDetail(Request $request, $id)
    {
        $data = $this->drawerService->getDrawerProductDetail($id);
        return response()->json(['code'=> 200, 'data'=> $data]);
    }

    public function nameMatch(Request $request)
    {
        $where['company'] = $request->input('company', '');
        $data = $this->drawerService->nameMatch($where);
        return response()->json(['code'=> 200, 'data'=> $data]);
    }

    public function drawerExport(Request $request, DrawerExport $drawerExport)
    {
        $where['status'] = 3;
        $param['userlimit'] = $this->getUserLimit();
        return $drawerExport->drawerListExport($where, $request->input('keyword'));
    }
}

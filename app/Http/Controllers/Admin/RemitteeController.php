<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\RemitteePostRequest;
use App\Services\Admin\RemitteeService;

class RemitteeController extends Controller
{
    public function __construct(RemitteeService $remitteeService)
    {
        parent::__construct();
        $this->remitteeService = $remitteeService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['cid'] = $this->getUserCid();
            $list = $this->remitteeService->remitteeIndexService($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }

        return view('admin.remittee.index');
    }

    public function read($type = 'read', $id = 0)
    {
        $view = view('admin.remittee.remdetail');
        $view->with('remit_type', $this->remitteeService->getRemitteeTypeService());
        if ($id) {
            $view->with('remittee', $this->remitteeService->findService($id));
        }
        return $this->disable($view);
    }

    public function save(RemitteePostRequest $request)
    {
        $param = $request->input();
        $param['status'] = 2;
        $res = $this->remitteeService->save($param);
        if($res['status']){
            return response()->json(['code'=>200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=>403, 'msg'=> $res['msg']]);
        }
    }

    public function update(RemitteePostRequest $request, $id)
    {
        $param = $request->input();
        $res = $this->remitteeService->update($param, $id);
        if($res['status']){
            return response()->json(['code'=>200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=>403, 'msg'=> $res['msg']]);
        }
    }

    //收款方选择
    public function remitteeChoose(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('admin.remittee.chooseRemittee');
        }else if($action == 'data'){
            $param = $request->input();
            $param['cid'] = $this->getUserCid();
            $data = $this->remitteeService->remitteeIndexService($param);
            return response()->json(['code'=>200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }
}

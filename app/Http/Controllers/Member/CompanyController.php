<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Requests\CompanyPostRequest;
use App\Services\Member\CompanyService;

class CompanyController extends Controller
{
    public function __construct(CompanyService $companyService)
    {
        parent::__construct();
        $this->companyService = $companyService;
    }

    public function index(Request $request, $status = 0)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->companyService->companyIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('member.company.index');
    }

    public function save(CompanyPostRequest $request)
    {

        $res = $this->companyService->save($request->input());
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function delete($id)
    {
        $res = $this->companyService->delete($id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function update(CompanyPostRequest $request, $id)
    {
        $res = $this->companyService->update($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function read($type = 'read', $id = 0)
    {
        $view = view('member.company.companydetail');
        if ($id) {
            $company = $this->companyService->find($id);
            $view->with('company', $company);
        }
        return $this->disable($view);
    }

    public function companyChoose(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('member.company.chooseCompany');
        }else if($action == 'data'){
            $param = $request->input();
            $param['cid'] = $this->getUserCid();
            $data = $this->companyService->companyIndex($param);
            return response()->json(['code'=> 200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\ClearancePostRequest;
use App\Exports\Admin\ClearanceExport;
use App\Services\Admin\ClearanceService;

class ClearanceController extends Controller
{
    public function __construct(ClearanceService $clearanceService)
    {
        parent::__construct();
        $this->clearanceService = $clearanceService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->clearanceService->clearanceIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('admin.clearance.index');
    }

    public function fileInfo(Request $request, $id)
    {
        if($request->isMethod('get')){
            $clearance = $this->clearanceService->find($id);
            return view('admin.clearance.file', compact('clearance'));
        }else{
            $res = $this->clearanceService->update($request->input(), $id);
            if($res['status']){
                return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
            }else{
                return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
            }
        }
    }

    public function customsEntryUpdate(ClearancePostRequest $request, $id)
    {
        $res = $this->clearanceService->customsEntryUpdate($request->except('pro'), $request->input('pro'), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function get($id)
    {
        return $this->clearanceService->get($id);
    }

    public function update(Request $request, $id)
    {
        $res = $this->clearanceService->update($request->input(), $id);
        if($res['status']){
            return response()->json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return response()->json(['code'=> 403, 'msg'=> $res['msg']]);
        }
    }

    public function clearanceExport(Request $request, ClearanceExport $clearanceExport)
    {
        $param = $request->input();
        $param['userlimit'] = $this->getUserLimit();
        return $clearanceExport->clearanceListExport($param);
    }
}

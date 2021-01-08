<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Curl\Curl;
use App\Services\Admin\CustomerfundService;
use App\Exports\Admin\CustomerfundExport;

class CustomerfundController extends Controller
{
    public function __construct(CustomerfundService $customerfundService)
    {
        parent::__construct();
        $this->customerfundService = $customerfundService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $param['keyword'] = $request->input('keyword', '');
            $param['pageSize'] = $request->input('pageSize', 10);
            $list = $this->customerfundService->customerfundIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('admin.customerfund.index');
    }

    public function detail(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            $customerfund_id = $request->input('customerfund_id', 0);
            return view('admin.customerfund.detail', compact('customerfund_id'));
        }else if($action == 'data'){
            $customerfund_id = $request->input('customerfund_id');
            $data = $this->customerfundService->detail($customerfund_id);
            return response()->json(['code'=> 200, 'data'=> $data]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }

    public function statementList(Request $request)
    {
        $param = $request->input();
        $param['pageSize'] = $request->input('pageSize', 10);
        $param['keyword'] = $request->input('keyword', '');
        $param['sdate'] = $request->input('sdate', '');
        $param['edate'] = $request->input('edate', '');
        $list = $this->customerfundService->statementList($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }

    public function customerfundExport(Request $request, CustomerfundExport $customerfundExport)
    {
        $param = $request->input();
        return $customerfundExport->customerfundListExport($param);
    }
}

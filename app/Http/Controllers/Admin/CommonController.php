<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\Admin\CommonService;

class CommonController extends Controller
{
    public function __construct(CommonService $commonService)
    {
        parent::__construct();
        $this->commonService = $commonService;
    }

    public function pendingReviewNumber(Request $request)
    {
        $param = $this->getUserLimit();
        $param[] = ['status', '<', 3];
        $count = $this->commonService->pendingReviewNumberService($param);
        return response()->json(['code'=> 200, 'data'=> $count]);
    }

    public function unitList(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('admin.common.unitList');
        }else if($action == 'data'){
            $param = $request->except('action');
            $list = $this->commonService->unitListService($param);
            return response()->json(['code'=> 200, 'data'=> $list]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }
    
    public function countryList(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('admin.common.countryList');
        }else if($action == 'data'){
            $param = $request->except('action');
            $list = $this->commonService->countryListService($param);
            return response()->json(['code'=> 200, 'data'=> $list]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }

    public function portList(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('admin.common.portList');
        }else if($action == 'data'){
            $param = $request->except('action');
            $list = $this->commonService->portListService($param);
            return response()->json(['code'=> 200, 'data'=> $list]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }

    public function districtList(Request $request)
    {
        $action = $request->input('action');
        if($action == 'html'){
            return view('admin.common.domesticSource');
        }else if($action == 'data'){
            $param = $request->except('action');
            $list = $this->commonService->districtListService($param);
            return response()->json(['code'=> 200, 'data'=> $list]);
        }
        return response()->json(['code'=> 400, 'msg'=> '无效参数']);
    }
}

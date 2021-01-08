<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Api\CommonService;

class CommonController extends BaseController
{
    public function __construct(CommonService $commonService)
    {
        parent::__construct();
        $this->commonService = $commonService;
    }

    public function countryIndex(Request $request)
    {
        $keyword = $request->input('keyword');
        $where = [['country_na', 'like', '%'. $keyword .'%']];
        $country = $this->commonService->countryIndexService($where);
        return response()->json(['code'=> 200, 'data'=> $country]);
    }

    public function portIndex(Request $request)
    {
        $country = $request->input('country');
        $keyword = $request->input('keyword');
        $where = [['port_c_cod', 'like', '%'. $keyword .'%']];
        $port = $this->commonService->getPortlistService($where, $country);
        return response()->json(['code'=> 200, 'data'=> $port]);
    }
    
    public function countryList(Request $request)
    {
        $param = $request->input();
        $list = $this->commonService->countryListService($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }

    public function unitList(Request $request)
    {
        $param = $request->input();
        $list = $this->commonService->unitListService($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }

    public function districtList(Request $request)
    {
        $param = $request->input();
        $list = $this->commonService->districtListService($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }
}

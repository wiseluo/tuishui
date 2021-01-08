<?php

namespace App\Http\Controllers\Erp;

use Illuminate\Http\Request;
use App\Http\Requests\Erp\CommonPostRequest;
use App\Services\Erp\CommonService;
use App\Data;
use App\Company;

class CommonController extends BaseController
{
    public function __construct(CommonService $commonService)
    {
        parent::__construct();
        $this->commonService = $commonService;
    }

    //获取经营公司列表
    public function getCompanyList(Request $request)
    {
        $company = Company::search($request->input('keyword'))->paginate(10)->toArray();
        if($company){
            $data = array_map(function($value){
                return ['id'=> $value['id'], 'name'=> $value['name']];
            }, $company['data']);
            $company['data'] = $data;
            return response()->json(['code'=>200, 'data'=> $company]);
        }else{
            return response()->json(['code'=>200, 'msg'=> '获取失败']);
        }
    }

    //获取数据维护
    public function getData(Request $request)
    {
        if(!$request->has('father_id')){
            return response()->json(['code'=>200, 'data'=> '参数不全']);
        }
        $data = Data::where('father_id', $request->input('father_id'))->get()->toArray();
        if($data){
            $data_return = array_map(function($value){
                $item = [];
                $item['id'] = $value['id'];
                $item['father_id'] = $value['father_id'];
                $item['name'] = $value['name'];
                $item['key'] = $value['key'];
                $item['value'] = $value['value'];
                return $item;
            }, $data);
            return response()->json(['code'=>200, 'data'=> $data_return]);
        }else{
            return response()->json(['code'=>200, 'msg'=> '获取失败']);
        }
        return response()->json(['code'=>200, 'data'=> $data]);
    }

    public function unitList(Request $request)
    {
        $param = $request->input();
        $list = $this->commonService->unitListService($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }
}

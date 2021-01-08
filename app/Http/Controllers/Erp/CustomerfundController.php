<?php

namespace App\Http\Controllers\Erp;

use Illuminate\Http\Request;
use App\Services\Erp\CustomerfundService;

class CustomerfundController extends BaseController
{
    public function __construct(CustomerfundService $customerfundService)
    {
        parent::__construct();
        $this->customerfundService = $customerfundService;
    }

    public function getCustomerfundList(Request $request)
    {
        $param['keyword'] = $request->input('keyword', '');
        $param['pageSize'] = $request->input('pageSize', 10);
        $list = $this->customerfundService->customerfundIndex($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }

}

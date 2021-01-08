<?php

namespace App\Http\Controllers\External;

use Illuminate\Http\Request;
use App\Services\External\ExternalService;

class OrderController extends BaseController
{
    public function __construct(ExternalService $externalService)
    {
        parent::__construct();
        $this->externalService = $externalService;
    }
    
    public function send($id)
    {
        $res = $this->externalService->sendOrder($id);
        return $res;
        //return response()->json(['code'=> 200, 'msg'=> $res]);
    }

    public function dsyOrderStatistics(Request $request)
    {
        $year = $request->input('year', date('Y', time()));
        $data = $this->externalService->dsyOrderStatisticsService($year);
        return response()->json(['code'=> 200, 'data'=> $data]);
    }

    public function dsyOrder(Request $request)
    {
        $param['year'] = $request->input('year', date('Y', time()));
        $param['pageSize'] = $request->input('pageSize', 10);
        $param['page'] = $request->input('page', 1);
        $data = $this->externalService->dsyOrderService($param);
        return response()->json(['code'=> 200, 'data'=> $data]);
    }
}

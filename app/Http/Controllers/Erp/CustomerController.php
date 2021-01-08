<?php

namespace App\Http\Controllers\Erp;

use Illuminate\Http\Request;
use App\Services\Erp\CustomerService;

class CustomerController extends BaseController
{
    public function __construct(CustomerService $customerService)
    {
        parent::__construct();
        $this->customerService = $customerService;
    }

    public function getCustomerList(Request $request)
    {
        $param['keyword'] = $request->input('keyword', '');
        $param['pageSize'] = $request->input('pageSize', 10);
        $param['status'] = 3;
        $list = $this->customerService->customerIndex($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }

}

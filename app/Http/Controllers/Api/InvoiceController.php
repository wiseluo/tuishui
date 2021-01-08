<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Api\InvoiceService;

class InvoiceController extends BaseController
{
    public function __construct(InvoiceService $invoiceService)
    {
        parent::__construct();
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $param = $request->input();
        $param['status'] = $request->input('status', 0);
        $where = $this->getBaseWhere();
        $param['cid'] = $where['cid'];
        $param['customer_id'] = $this->getCustomerid();
        $list = $this->invoiceService->invoiceIndex($param);
        return response()->json(['code'=>200, 'data'=> $list]);
    }

    public function show($id)
    {
        $data = $this->invoiceService->showService($id);
        return response()->json(['code'=>200, 'data'=> $data]);
    }
}

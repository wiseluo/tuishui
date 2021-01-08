<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\InvoicePostRequest;
use App\Services\Admin\InvoiceService;
use App\Exports\Admin\InvoiceExport;

class InvoiceController extends Controller
{
    public function __construct(InvoiceService $invoiceService)
    {
        parent::__construct();
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request, $status = 0)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['status'] = $status;
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->invoiceService->invoiceIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('admin.invoice.index');
    }

    public function read($type = 'read', $id = 0)
    {
        $view = view('admin.invoice.invdetail');
        if ($id) {
            $view->with('invoice', $this->invoiceService->find($id));
        }
        return $this->disable($view);
    }

    public function orderList(Request $request)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['userlimit'] = $this->getUserLimit();
            $list = $this->invoiceService->orderListService($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('admin.invoice.orderlist');
    }

    public function orderInvoiceList(Request $request, $id)
    {
        $list = $this->invoiceService->orderInvoiceListService($id);
        return response()->json(['code'=>200, 'data'=> $list]);
    }

    public function invoiceExport(Request $request, InvoiceExport $invoiceExport)
    {
        $param = $request->input();
        $param['userlimit'] = $this->getUserLimit();
        return $invoiceExport->invoiceListExport($param);
    }
}

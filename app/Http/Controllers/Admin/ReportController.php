<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\Admin\ReportService;
use App\Exports\Admin\ReportExport;

class ReportController extends Controller
{
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $param = $request->input();
            $param['cid'] = $this->getUserCid();
            $list = $this->reportService->reportIndex($param);
            return response()->json(['code'=>200, 'total'=> $list['total'], 'rows'=> $list['data']]);
        }
        return view('admin.report.index');
    }

    public function reportExport(Request $request, ReportExport $reportExport)
    {
        $param = $request->input();
        $param['cid'] = $this->getUserCid();
        return $reportExport->reportExport($param);
    }
}

<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Services\Member\ReportService;

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
        return view('member.report.index');
    }

}

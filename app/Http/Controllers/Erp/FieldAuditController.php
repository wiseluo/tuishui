<?php

namespace App\Http\Controllers\Erp;

use Illuminate\Http\Request;
use App\Services\Erp\FieldAuditService;

class FieldAuditController extends BaseController
{
    public function __construct(FieldAuditService $fieldAuditService)
    {
        parent::__construct();
        $this->fieldAuditService = $fieldAuditService;
    }

    public function drawerList(Request $request)
    {
        $list = $this->fieldAuditService->drawerListService($request->input());
        return response()->json(['code'=>200, 'data'=> $list]);
    }

}

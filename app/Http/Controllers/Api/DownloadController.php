<?php

namespace App\Http\Controllers\Api;

use App\Services\Member\InvoiceMaterialExcelService;
use App\Services\Member\ClearanceMaterialExcelService;

class DownloadController extends BaseController
{
    public function __construct(ClearanceMaterialExcelService $clearanceMaterialExcelService, InvoiceMaterialExcelService $invoiceMaterialExcelService)
    {
        parent::__construct();
        $this->clearanceMaterialExcelService = $clearanceMaterialExcelService;
        $this->invoiceMaterialExcelService = $invoiceMaterialExcelService;
    }

    //报关资料下载
    public function clearanceMaterialExcel($id)
    {
        return $this->clearanceMaterialExcelService->clearanceMaterialExport($id);
    }

    //开票资料下载
    public function invoiceMaterialExcel($id)
    {
        return $this->invoiceMaterialExcelService->invoiceMaterialExport($id);
    }
    
}

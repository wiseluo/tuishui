<?php

namespace App\Http\Controllers\Member;

use App\Services\Member\InvoiceMaterialExcelService;
use App\Services\Member\ClearanceMaterialExcelService;
use App\Services\Member\CustomsOrderExcelService;

class DownloadController extends Controller
{
    public function __construct(ClearanceMaterialExcelService $clearanceMaterialExcelService, InvoiceMaterialExcelService $invoiceMaterialExcelService, CustomsOrderExcelService $customsOrderExcelService)
    {
        parent::__construct();
        $this->clearanceMaterialExcelService = $clearanceMaterialExcelService;
        $this->invoiceMaterialExcelService = $invoiceMaterialExcelService;
        $this->customsOrderExcelService = $customsOrderExcelService;
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

    //报关预录入单下载
    public function customsOrderExcel($id)
    {
        return $this->customsOrderExcelService->customsOrderExport($id);
    }
}

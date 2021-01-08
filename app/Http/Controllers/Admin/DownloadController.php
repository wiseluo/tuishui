<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\ClearanceMaterialExcelService;
use App\Services\Admin\CustomsOrderExcelService;

class DownloadController extends Controller
{
    public function __construct(ClearanceMaterialExcelService $clearanceMaterialExcelService, CustomsOrderExcelService $customsOrderExcelService)
    {
        parent::__construct();
        $this->clearanceMaterialExcelService = $clearanceMaterialExcelService;
        $this->customsOrderExcelService = $customsOrderExcelService;
    }

    //报关资料下载
    public function clearanceMaterialExcel($id)
    {
        return $this->clearanceMaterialExcelService->clearanceMaterialExport($id);
    }

    //报关预录入单下载
    public function customsOrderExcel($id)
    {
        return $this->customsOrderExcelService->customsOrderExport($id);
    }
}

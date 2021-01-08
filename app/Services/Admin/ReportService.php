<?php

namespace App\Services\Admin;

use App\Repositories\ReportRepository;

class ReportService
{
    const SCALE = 2;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function reportIndex($param)
    {
        $list = $this->reportRepository->reportIndex($param);
        //dd($list);
        $data = array_map(function($value){
            $item = [];
            $item['id'] = $value['id'];
            $item['export_date'] = $value['order']['export_date'];
            $item['ordnumber'] = $value['order']['ordnumber'];
            $item['drawer_company'] = $value['drawer_product']['drawer']['company'];

            $invoice_number = '';
            $invoice_quantity = 0;
            $invoice_amount = 0;
            $filing_returned_at = '';
            if(isset($value['invoice']) && count($value['invoice']) > 0){
                foreach($value['invoice'] as $k => $v){
                    $invoice_number .= ($invoice_number == '' ? $v['number'] :','. $v['number']);
                    $filing_returned_at = $v['filing']['returned_at'];
                    $invoice_quantity = bcadd($invoice_quantity, $v['pivot']['quantity']);
                    $invoice_amount = bcadd($invoice_amount, $v['pivot']['amount']);
                }
            }
            $item['invoice_number'] = $invoice_number;
            $item['invoice_quantity'] = $invoice_quantity;
            $item['invoice_amount'] = $invoice_amount;
            $item['customer_name'] = $value['order']['customer']['name'];
            $item['returned_at'] = $filing_returned_at;
            $item['customs_number'] = $value['order']['customs_number'];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

}

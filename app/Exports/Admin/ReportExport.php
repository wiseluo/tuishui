<?php

namespace App\Exports\Admin;

use App\Repositories\ReportRepository;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Border;

class ReportExport
{
    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function reportExport($param)
    {
        $report = $this->reportRepository->reportExportData($param);
        //dd($report);
        $objPHPExcel = new PHPExcel();
        $objActSheet = $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet->getDefaultColumnDimension()->setWidth(14); //设置默认列宽度
        $objActSheet->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // 默认水平居中
        $objActSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // 默认垂直居中

        $styleThinBlackBorderOutline= array(
            'borders' => array (
                'allborders' => array (
                    'style' => PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
                    'color' => array ('argb' => 'FF000000'),       //设置border颜色
                ),
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A1:BQ1')->getFont()->setBold(true);

        $objActSheet->setCellValueExplicit('A1', '序号');
        $objActSheet->getColumnDimension('A')->setWidth(10);
        $objActSheet->setCellValueExplicit('B1', '类型');
        $objActSheet->getColumnDimension('B')->setWidth(10);
        $objActSheet->setCellValueExplicit('C1', '出口日期');
        $objActSheet->setCellValueExplicit('D1', '录入日期');
        $objActSheet->setCellValueExplicit('E1', '报关日期');
        $objActSheet->setCellValueExplicit('F1', '采购');
        $objActSheet->setCellValueExplicit('G1', '柜号');
        $objActSheet->setCellValueExplicit('H1', '操作');
        $objActSheet->setCellValueExplicit('I1', '单证操作员');
        $objActSheet->setCellValueExplicit('J1', '货源地');
        $objActSheet->setCellValueExplicit('K1', '合同号');
        $objActSheet->getColumnDimension('K')->setWidth(18);
        $objActSheet->setCellValueExplicit('L1', '产品大类');
        $objActSheet->setCellValueExplicit('M1', 'HS编码');
        $objActSheet->setCellValueExplicit('N1', '品名');
        $objActSheet->getColumnDimension('N')->setWidth(30);
        $objActSheet->setCellValueExplicit('O1', '退税率');
        $objActSheet->setCellValueExplicit('P1', '报关美金');
        $objActSheet->setCellValueExplicit('Q1', '单价');
        $objActSheet->setCellValueExplicit('R1', '数量');
        $objActSheet->setCellValueExplicit('S1', '单位');
        $objActSheet->setCellValueExplicit('T1', '开票时间');
        $objActSheet->setCellValueExplicit('U1', '工厂名称');
        $objActSheet->getColumnDimension('U')->setWidth(40);
        $objActSheet->setCellValueExplicit('V1', '发票号');
        $objActSheet->getColumnDimension('V')->setWidth(30);
        $objActSheet->setCellValueExplicit('W1', '开票数量');
        $objActSheet->setCellValueExplicit('X1', '单价');
        $objActSheet->setCellValueExplicit('Y1', '单位');
        $objActSheet->setCellValueExplicit('Z1', '开票金额');
        $objActSheet->setCellValueExplicit('AA1', '合同');
        $objActSheet->setCellValueExplicit('AB1', '付款');
        $objActSheet->getColumnDimension('AB')->setWidth(40);
        $objActSheet->setCellValueExplicit('AC1', '应退税款');
        $objActSheet->setCellValueExplicit('AD1', '申报时的退税款');
        $objActSheet->setCellValueExplicit('AE1', '申请日期');
        $objActSheet->setCellValueExplicit('AF1', '付款日期');
        $objActSheet->setCellValueExplicit('AG1', '预提日期');
        $objActSheet->setCellValueExplicit('AH1', '应付款');
        $objActSheet->setCellValueExplicit('AI1', '国税');
        $objActSheet->setCellValueExplicit('AJ1', '佣金');
        $objActSheet->setCellValueExplicit('AK1', '地税');
        $objActSheet->setCellValueExplicit('AL1', '结算日期');
        $objActSheet->setCellValueExplicit('AM1', '利润');
        $objActSheet->setCellValueExplicit('AN1', '客户名');
        $objActSheet->getColumnDimension('AN')->setWidth(30);
        $objActSheet->setCellValueExplicit('AO1', '结算方式');
        $objActSheet->setCellValueExplicit('AP1', '是否集团客户');
        $objActSheet->setCellValueExplicit('AQ1', '批次');
        $objActSheet->setCellValueExplicit('AR1', '退回日期');
        $objActSheet->setCellValueExplicit('AS1', '业务员奖励核算');
        $objActSheet->setCellValueExplicit('AT1', '提单号');
        $objActSheet->setCellValueExplicit('AU1', '报关单号');
        $objActSheet->getColumnDimension('AU')->setWidth(20);
        $objActSheet->setCellValueExplicit('AV1', '箱号');
        $objActSheet->setCellValueExplicit('AW1', '国家');
        $objActSheet->setCellValueExplicit('AX1', '出发港');
        $objActSheet->setCellValueExplicit('AY1', '件数');
        $objActSheet->setCellValueExplicit('AZ1', '立方数');
        $objActSheet->setCellValueExplicit('BA1', '柜型');
        $objActSheet->setCellValueExplicit('BB1', '装柜地');
        $objActSheet->setCellValueExplicit('BC1', '证件');
        $objActSheet->setCellValueExplicit('BD1', '后补');
        $objActSheet->setCellValueExplicit('BE1', '放行通知书');
        $objActSheet->setCellValueExplicit('BF1', '提单');
        $objActSheet->setCellValueExplicit('BG1', '备案资料');
        $objActSheet->setCellValueExplicit('BH1', '货代开票名称');
        $objActSheet->setCellValueExplicit('BI1', '货代开票时间');
        $objActSheet->setCellValueExplicit('BJ1', '开票号码');
        $objActSheet->setCellValueExplicit('BK1', '货代明细金额');
        $objActSheet->setCellValueExplicit('BL1', '大票金额');
        $objActSheet->setCellValueExplicit('BM1', '车队开票名称');
        $objActSheet->setCellValueExplicit('BN1', '开票时间');
        $objActSheet->setCellValueExplicit('BO1', '开票号码');
        $objActSheet->setCellValueExplicit('BP1', '车队明细金额');
        $objActSheet->setCellValueExplicit('BQ1', '大票金额');

        $row = 2;
        $serial_no = [];
        $report->each(function($item, $key)use(&$row, &$serial_no, $objActSheet){
            if(!array_key_exists($item->order_id, $serial_no)){
                $serial_no[$item->order_id] = count($serial_no) +1;
                $objActSheet->setCellValueExplicit('A'. $row, $serial_no[$item->order_id]);
            }
            $objActSheet->setCellValueExplicit('B'. $row, $item->order->customer->cusclassify);
            $objActSheet->setCellValueExplicit('C'. $row, $item->order->export_date);
            $objActSheet->setCellValueExplicit('D'. $row, date('Y-m-d', strtotime($item->order->created_at)));
            $objActSheet->setCellValueExplicit('E'. $row, $item->order->customs_at);
            // $objActSheet->setCellValueExplicit('F'. $row, $value['order']['operator']);
            // $objActSheet->setCellValueExplicit('G'. $row, $value['order']['export_date']);
            $objActSheet->setCellValueExplicit('H'. $row, $item->order->operator);
            $objActSheet->setCellValueExplicit('I'. $row, $item->order->created_user_name);
            $objActSheet->setCellValueExplicit('J'. $row, $item->domestic_source);
            $objActSheet->setCellValueExplicit('K'. $row, $item->order->ordnumber);
            // $objActSheet->setCellValueExplicit('L'. $row, $value['order']['created_user_name']);
            $objActSheet->setCellValueExplicit('M'. $row, $item->drawerProduct->product->hscode);
            $objActSheet->setCellValueExplicit('N'. $row, $item->drawerProduct->product->name);
            $objActSheet->setCellValueExplicit('O'. $row, $item->drawerProduct->product->tax_refund_rate);
            $objActSheet->setCellValueExplicit('P'. $row, $item->total_price);
            $objActSheet->setCellValueExplicit('Q'. $row, $item->single_price);
            $objActSheet->setCellValueExplicit('R'. $row, $item->number);
            $objActSheet->setCellValueExplicit('S'. $row, $item->unit);

            $invoice_number = '';
            $invoice_billed_at = '';
            $filing_returned_at = '';
            $filing_bath = '';
            $invoice_quantity = 0;
            $invoice_amount = 0;
            $refund_tax_amount_sum = 0;
            if(isset($item->invoice) && count($item->invoice->toArray()) > 0){
                $invoice = $item->invoice->toArray();
                foreach($invoice as $k => $v){
                    $invoice_number .= ($invoice_number == '' ? $v['number'] :','. $v['number']);
                    $invoice_billed_at = $v['billed_at'];
                    $filing_returned_at = $v['filing']['returned_at'];
                    $filing_bath = $v['filing']['batch'];
                    $invoice_quantity = bcadd($invoice_quantity, $v['pivot']['quantity']);
                    $invoice_amount = bcadd($invoice_amount, $v['pivot']['amount']);
                    $refund_tax_amount_sum = bcadd($invoice_amount, $v['pivot']['refund_tax_amount']);
                }
            }
            $objActSheet->setCellValueExplicit('T'. $row, $invoice_billed_at);
            $objActSheet->setCellValueExplicit('U'. $row, $item->company);
            $objActSheet->setCellValueExplicit('V'. $row, $invoice_number);
            $objActSheet->setCellValueExplicit('W'. $row, $invoice_quantity);
            //$objActSheet->setCellValueExplicit('X'. $row, $invoice_number);
            //$objActSheet->setCellValueExplicit('Y'. $row, $invoice_number);
            $objActSheet->setCellValueExplicit('Z'. $row, $invoice_amount);
            $objActSheet->setCellValueExplicit('AA'. $row, '');
            $objActSheet->setCellValueExplicit('AB'. $row, '');
            $objActSheet->setCellValueExplicit('AC'. $row, $refund_tax_amount_sum);
            $objActSheet->setCellValueExplicit('AD'. $row, '');
            $objActSheet->setCellValueExplicit('AE'. $row, '');
            $objActSheet->setCellValueExplicit('AF'. $row, '');
            $objActSheet->setCellValueExplicit('AG'. $row, '');
            $objActSheet->setCellValueExplicit('AH'. $row, '');
            $objActSheet->setCellValueExplicit('AI'. $row, '');
            $objActSheet->setCellValueExplicit('AJ'. $row, '');
            $objActSheet->setCellValueExplicit('AK'. $row, '');
            $objActSheet->setCellValueExplicit('AL'. $row, '');
            $objActSheet->setCellValueExplicit('AM'. $row, '');
            $objActSheet->setCellValueExplicit('AN'. $row, $item->order->customer->name);
            $objActSheet->setCellValueExplicit('AO'. $row, '');
            $objActSheet->setCellValueExplicit('AP'. $row, '');
            $objActSheet->setCellValueExplicit('AQ'. $row, $filing_bath);
            $objActSheet->setCellValueExplicit('AR'. $row, $filing_returned_at);
            $objActSheet->setCellValueExplicit('AS'. $row, '');
            $objActSheet->setCellValueExplicit('AT'. $row, $item->order->tdnumber);
            $objActSheet->setCellValueExplicit('AU'. $row, $item->order->customs_number);
            $objActSheet->setCellValueExplicit('AV'. $row, $item->order->box_number);
            $objActSheet->setCellValueExplicit('AW'. $row, isset($item->order->tradecountry->country_na) ? $item->order->tradecountry->country_na : '');
            $objActSheet->setCellValueExplicit('AX'. $row, $item->order->shipmentport->port_c_cod);
            $objActSheet->setCellValueExplicit('AY'. $row, $item->pack_number);
            $objActSheet->setCellValueExplicit('AZ'. $row, $item->volume);
            $objActSheet->setCellValueExplicit('BA'. $row, $item->order->box_type);
            $objActSheet->setCellValueExplicit('BB'. $row, '');
            $objActSheet->setCellValueExplicit('BC'. $row, '');
            $objActSheet->setCellValueExplicit('BD'. $row, '');
            $objActSheet->setCellValueExplicit('BE'. $row, '');
            $objActSheet->setCellValueExplicit('BF'. $row, '');
            $objActSheet->setCellValueExplicit('BG'. $row, '');
            $objActSheet->setCellValueExplicit('BH'. $row, '');
            $objActSheet->setCellValueExplicit('BI'. $row, '');
            $objActSheet->setCellValueExplicit('BJ'. $row, '');
            $objActSheet->setCellValueExplicit('BK'. $row, '');
            $objActSheet->setCellValueExplicit('BL'. $row, '');
            $objActSheet->setCellValueExplicit('BM'. $row, '');
            $objActSheet->setCellValueExplicit('BN'. $row, '');
            $objActSheet->setCellValueExplicit('BO'. $row, '');
            $objActSheet->setCellValueExplicit('BP'. $row, '');
            $objActSheet->setCellValueExplicit('BQ'. $row, '');
            $row++;
        });

        $objActSheet->getStyle('A1:BQ'. ($row-1))->applyFromArray($styleThinBlackBorderOutline);

        $fileName = date('Y') ."退税明细.xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}

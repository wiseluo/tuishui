<?php

namespace App\Exports\Admin;

use App\Repositories\FilingRepository;
use App\Services\Admin\FilingService;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Border;

class FilingExport
{
    protected $filingRepository;
    protected $filingService;

    public function __construct(FilingRepository $filingRepository, FilingService $filingService)
    {
        $this->filingRepository = $filingRepository;
        $this->filingService = $filingService;
    }

    public function filingListExport($param)
    {
        //已完成退税的申报单
        $filing = $this->filingRepository->getFilingExportData($param);
        //dd($filing);

        //未完成退税，在申报流程的申报单，去erp取
        $erp_filing_res = $this->filingService->getFilingExportFromErp($param);
        if($erp_filing_res['code'] == 200){
            $erp_filing = $erp_filing_res['data'];
        }else{
            $erp_filing = [];
        }
        $objPHPExcel = new PHPExcel();
        $objActSheet = $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet->getDefaultColumnDimension()->setWidth(14); //设置默认列宽度
        $objActSheet->getColumnDimension('H')->setWidth(30);
        $objActSheet->getColumnDimension('I')->setWidth(30);
        $objActSheet->getColumnDimension('J')->setWidth(20);
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

        $objActSheet->mergeCells('A1:L1');
        $objActSheet->getRowDimension(1)->setRowHeight(40);
        $objActSheet->getStyle('A1')->getFont()->setBold(true);
        $objActSheet->getStyle('A1')->getFont()->setSize(20);
        $first_row = '申报管理——关键词：';
        $param['keyword'] != '' ? $first_row .= $param['keyword'] : '';
        $objActSheet->setCellValueExplicit('A1', $first_row, PHPExcel_Cell_DataType::TYPE_STRING);

        $objActSheet->setCellValueExplicit('A2', '序号');
        $objActSheet->setCellValueExplicit('B2', '订单号');
        $objActSheet->setCellValueExplicit('C2', '状态');
        $objActSheet->setCellValueExplicit('D2', '申报日期');
        $objActSheet->setCellValueExplicit('E2', '申报批次');
        $objActSheet->setCellValueExplicit('F2', '退税款金额');
        $objActSheet->setCellValueExplicit('G2', '退回日期');
        $objActSheet->setCellValueExplicit('H2', '开票工厂');
        $objActSheet->setCellValueExplicit('I2', '客户');
        $objActSheet->setCellValueExplicit('J2', '报关单号');
        $objActSheet->setCellValueExplicit('K2', '开票金额');
        $objActSheet->setCellValueExplicit('L2', '开票日期');

        $data = [];
        array_map(function($item)use(&$data){
            $data[] = [
                'ordnumber' => $item['ordnumber'],
                'status' => '已申报',
                'applied_at' => $item['applied_at'],
                'batch' => $item['batch'],
                'refund_tax_amount_sum' => $item['refund_tax_amount_sum'],
                'returned_at' => $item['returned_at'],
                'company' => $item['company'],
                'customer' => '',
                'customs_number' => $item['customs_number'],
                'invoice_amount' => $item['invoice_amount'],
                'billed_at' => $item['billed_at'],
            ];
        }, $erp_filing);
        $filing->each(function($item, $key)use(&$data){
            $refund_tax_amount_sum = $item->drawerProductOrders->reduce(function ($carry, $i) {
                return bcadd($carry, $i->pivot->refund_tax_amount, 2);
            }, 0);
            $data[] = [
                'ordnumber' => $item->order->ordnumber,
                'status' => '已退税',
                'applied_at' => $item->filing->applied_at,
                'batch' => $item->filing->batch,
                'refund_tax_amount_sum' => $refund_tax_amount_sum,
                'returned_at' => $item->filing->returned_at,
                'company' => $item->drawer->company,
                'customer' => $item->drawer->customer->name,
                'customs_number' => $item->order->customs_number,
                'invoice_amount' => $item->invoice_amount,
                'billed_at' => $item->billed_at,
            ];
        });

        $row = 3;
        foreach ($data as $v) {
            $objActSheet->setCellValueExplicit('A'. $row, ($row-2));
            $objActSheet->setCellValueExplicit('B'. $row, $v['ordnumber']);
            $objActSheet->setCellValueExplicit('C'. $row, $v['status']);
            $objActSheet->setCellValueExplicit('D'. $row, $v['applied_at']);
            $objActSheet->setCellValueExplicit('E'. $row, $v['batch']);
            $objActSheet->setCellValueExplicit('F'. $row, $v['refund_tax_amount_sum']);
            $objActSheet->setCellValueExplicit('G'. $row, $v['returned_at']);
            $objActSheet->setCellValueExplicit('H'. $row, $v['company']);
            $objActSheet->setCellValueExplicit('I'. $row, $v['customer']);
            $objActSheet->setCellValueExplicit('J'. $row, $v['customs_number']);
            $objActSheet->setCellValueExplicit('K'. $row, $v['invoice_amount']);
            $objActSheet->setCellValueExplicit('L'. $row, $v['billed_at']);
            $row++;
        }
        $objActSheet->getStyle('A2:L'. ($row-1))->applyFromArray($styleThinBlackBorderOutline);

        $fileName  = $first_row;
        $fileName .= ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function filingYearExport($param)
    {
        //已完成退税的申报单
        $filing = $this->filingRepository->getFilingYearExportData($param);
        
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

        $objActSheet->mergeCells('A1:G1');
        $objActSheet->getRowDimension(1)->setRowHeight(40);
        $objActSheet->getStyle('A1')->getFont()->setBold(true);
        $objActSheet->getStyle('A1')->getFont()->setSize(20);
        $first_row = date('Y').'年';
        $objActSheet->setCellValueExplicit('A1', $first_row, PHPExcel_Cell_DataType::TYPE_STRING);

        $objActSheet->setCellValueExplicit('A2', '批次');
        $objActSheet->setCellValueExplicit('B2', '美金金额');
        $objActSheet->setCellValueExplicit('C2', '退税金额');
        $objActSheet->setCellValueExplicit('D2', '退回日期');
        $objActSheet->setCellValueExplicit('E2', '票数');
        $objActSheet->setCellValueExplicit('F2', '申报日期');
        $objActSheet->setCellValueExplicit('G2', '所属期');

        $filing->each(function($item, $key)use(&$data){
            $data[] = [
                'batch' => $item->batch,
                'usd_amount' => '',
                'amount' => $item->amount,
                'returned_at' => $item->returned_at,
                'invoice_quantity' => $item->invoice_quantity,
                'applied_at' => $item->applied_at,
                'belongs_at' => '',
            ];
        });

        $row = 3;
        foreach ($data as $v) {
            $objActSheet->setCellValueExplicit('A'. $row, $v['batch']);
            $objActSheet->setCellValueExplicit('B'. $row, $v['usd_amount']);
            $objActSheet->setCellValueExplicit('C'. $row, $v['amount']);
            $objActSheet->setCellValueExplicit('D'. $row, $v['returned_at']);
            $objActSheet->setCellValueExplicit('E'. $row, $v['invoice_quantity']);
            $objActSheet->setCellValueExplicit('F'. $row, $v['applied_at']);
            $objActSheet->setCellValueExplicit('G'. $row, $v['belongs_at']);
            $row++;
        }
        $objActSheet->getStyle('A2:G'. ($row-1))->applyFromArray($styleThinBlackBorderOutline);

        $fileName  = $first_row;
        $fileName .= "退税款申报明细.xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}

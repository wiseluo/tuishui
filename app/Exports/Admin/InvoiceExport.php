<?php

namespace App\Exports\Admin;

use App\Repositories\InvoiceRepository;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Border;

class InvoiceExport
{
    protected $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function invoiceListExport($param)
    {
        $invoice = $this->invoiceRepository->getInvoiceExportData($param);
        //dd($invoice->toArray());
        $objPHPExcel = new PHPExcel();
        $objActSheet = $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet->getDefaultColumnDimension()->setWidth(14); //设置默认列宽度
        $objActSheet->getColumnDimension('C')->setWidth(30);
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
        $first_row = '发票管理——关键词：';
        $param['keyword'] != '' ? $first_row .= $param['keyword'] : '';
        $objActSheet->setCellValueExplicit('A1', $first_row, PHPExcel_Cell_DataType::TYPE_STRING);

        $objActSheet->setCellValueExplicit('A2', '序号');
        $objActSheet->setCellValueExplicit('B2', '订单号');
        $objActSheet->setCellValueExplicit('C2', '开票工厂');
        $objActSheet->setCellValueExplicit('D2', '开票时间');
        $objActSheet->setCellValueExplicit('E2', '开票金额');
        $objActSheet->setCellValueExplicit('F2', '状态');
        $objActSheet->setCellValueExplicit('G2', '退税款');

        $data = [];
        $invoice->each(function($item, $key)use(&$data){
            $refund_tax_amount_sum = $item->drawerProductOrders->reduce(function ($carry, $i) {
                return bcadd($carry, $i->pivot->refund_tax_amount, 2);
            });
            $data[] = [
                'ordnumber' => $item->order->ordnumber,
                'company' => $item->drawer->company,
                'billed_at' => $item->billed_at,
                'invoice_amount' => $item->invoice_amount,
                'status' => $item->status_str,
                'refund_tax_amount_sum' => $refund_tax_amount_sum,
            ];
        });

        $row = 3;
        foreach ($data as $v) {
            $objActSheet->setCellValueExplicit('A'. $row, ($row-2));
            $objActSheet->setCellValueExplicit('B'. $row, $v['ordnumber']);
            $objActSheet->setCellValueExplicit('C'. $row, $v['company']);
            $objActSheet->setCellValueExplicit('D'. $row, $v['billed_at']);
            $objActSheet->setCellValueExplicit('E'. $row, $v['invoice_amount']);
            $objActSheet->setCellValueExplicit('F'. $row, $v['status']);
            $objActSheet->setCellValueExplicit('G'. $row, $v['refund_tax_amount_sum']);
            $row++;
        }
        $objActSheet->getStyle('A2:G'. ($row-1))->applyFromArray($styleThinBlackBorderOutline);

        $fileName  = $first_row;
        $fileName .= ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}

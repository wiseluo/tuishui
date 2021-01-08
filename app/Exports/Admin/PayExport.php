<?php

namespace App\Exports\Admin;

use App\Repositories\PayRepository;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Border;

class PayExport
{
    protected $payRepository;

    public function __construct(PayRepository $payRepository)
    {
        $this->payRepository = $payRepository;
    }

    public function payExport($param)
    {
        $pay = $this->payRepository->getPayExportData($param);
        
        $objPHPExcel = new PHPExcel();
        $objActSheet = $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet->getDefaultColumnDimension()->setWidth(18); //设置默认列宽度
        $objActSheet->getColumnDimension('C')->setWidth(40);
        $objActSheet->getColumnDimension('D')->setWidth(40);
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

        $objActSheet->setCellValueExplicit('A1', '时间');
        $objActSheet->setCellValueExplicit('B1', '金额');
        $objActSheet->setCellValueExplicit('C1', '工厂');
        $objActSheet->setCellValueExplicit('D1', '客户名');
        $objActSheet->setCellValueExplicit('E1', '合同号');

        $pay->each(function($item, $key)use(&$data){
            $ordnumber = $item->order->reduce(function($carry, $i){
                return $carry == '' ? $i->ordnumber : $carry .','. $i->ordnumber;
            }, '');
            $data[] = [
                'applied_at' => $item->applied_at,
                'money' => $item->money,
                'remittee_name' => $item->remittee->name,
                'customer_name' => $item->customer->name,
                'ordnumber' => $ordnumber,
            ];
        });

        $row = 2;
        foreach ($data as $v) {
            $objActSheet->setCellValueExplicit('A'. $row, $v['applied_at']);
            $objActSheet->setCellValueExplicit('B'. $row, $v['money']);
            $objActSheet->setCellValueExplicit('C'. $row, $v['remittee_name']);
            $objActSheet->setCellValueExplicit('D'. $row, $v['customer_name']);
            $objActSheet->setCellValueExplicit('E'. $row, $v['ordnumber']);
            $row++;
        }
        $objActSheet->getStyle('A1:E'. ($row-1))->applyFromArray($styleThinBlackBorderOutline);

        $fileName = "定金.xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}

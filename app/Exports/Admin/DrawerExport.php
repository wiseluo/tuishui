<?php

namespace App\Exports\Admin;

use App\Repositories\DrawerRepository;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Border;

class DrawerExport
{
    protected $drawerRepository;

    public function __construct(DrawerRepository $drawerRepository)
    {
        $this->drawerRepository = $drawerRepository;
    }

    public function drawerListExport($param)
    {
        $drawer = $this->drawerRepository->getDrawerList($param);

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

        $objActSheet->mergeCells('A1:F1');
        $objActSheet->getRowDimension(1)->setRowHeight(40);
        $objActSheet->getStyle('A1')->getFont()->setBold(true);
        $objActSheet->getStyle('A1')->getFont()->setSize(20);
        $first_row = '开票人管理';
        $param['keyword'] != '' ? $first_row .= '——关键词：'. $param['keyword'] : '';
        $objActSheet->setCellValueExplicit('A1', $first_row, PHPExcel_Cell_DataType::TYPE_STRING);

        $objActSheet->setCellValueExplicit('A2', '开票人名称');
        $objActSheet->setCellValueExplicit('B2', '纳税人识别号');
        $objActSheet->setCellValueExplicit('C2', '提交时间');
        $objActSheet->setCellValueExplicit('D2', '客户名称');
        $objActSheet->setCellValueExplicit('E2', '业务员');
        $objActSheet->setCellValueExplicit('F2', '单证员');

        $data = [];
        $drawer->each(function($item, $key)use(&$data){
            $data[] = [
                'company' => $item->company,
                'tax_id' => $item->tax_id,
                'created_at' => $item->created_at,
                'customer_name' => $item->customer->name,
                'customer_salesman' => $item->customer->salesman,
                'customer_created_user_name' => $item->customer->created_user_name,
            ];
        });

        $row = 3;
        foreach ($data as $v) {
            $objActSheet->setCellValueExplicit('A'. $row, $v['company']);
            $objActSheet->setCellValueExplicit('B'. $row, $v['tax_id']);
            $objActSheet->setCellValueExplicit('C'. $row, $v['created_at']);
            $objActSheet->setCellValueExplicit('D'. $row, $v['customer_name']);
            $objActSheet->setCellValueExplicit('E'. $row, $v['customer_salesman']);
            $objActSheet->setCellValueExplicit('F'. $row, $v['customer_created_user_name']);
            $row++;
        }
        $objActSheet->getStyle('A2:F'. ($row-1))->applyFromArray($styleThinBlackBorderOutline);

        $fileName  = $first_row;
        $fileName .= ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}

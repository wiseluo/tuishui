<?php

namespace App\Exports\Admin;

use App\Repositories\CustomerfundRepository;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Border;

class CustomerfundExport
{
    public function __construct(CustomerfundRepository $customerfundRepository)
    {
        $this->customerfundRepository = $customerfundRepository;
    }

    public function customerfundListExport($param)
    {
        $customer = $this->customerfundRepository->getCustomerList($param);

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

        $objActSheet->mergeCells('A1:H1');
        $objActSheet->getRowDimension(1)->setRowHeight(40);
        $objActSheet->getStyle('A1')->getFont()->setBold(true);
        $objActSheet->getStyle('A1')->getFont()->setSize(20);
        $first_row = '客户管理';
        $param['keyword'] != '' ? $first_row .= '——关键词：'. $param['keyword'] : '';
        $objActSheet->setCellValueExplicit('A1', $first_row, PHPExcel_Cell_DataType::TYPE_STRING);

        $objActSheet->setCellValueExplicit('A2', '客户名称');
        $objActSheet->setCellValueExplicit('B2', '客户编号');
        $objActSheet->setCellValueExplicit('C2', '创建时间');
        $objActSheet->setCellValueExplicit('D2', '联系人');
        $objActSheet->setCellValueExplicit('E2', '地址');
        $objActSheet->setCellValueExplicit('F2', '联系电话');
        $objActSheet->setCellValueExplicit('G2', '单证员');
        $objActSheet->setCellValueExplicit('H2', '业务员');

        $data = [];
        $customer->each(function($item, $key)use(&$data){
            $data[] = [
                'name' => $item->name,
                'number' => $item->number,
                'created_at' => $item->created_at,
                'linkman' => $item->linkman,
                'address' => $item->address,
                'telephone' => $item->telephone,
                'created_user_name' => $item->created_user_name,
                'salesman' => $item->salesman,
            ];
        });

        $row = 3;
        foreach ($data as $v) {
            $objActSheet->setCellValueExplicit('A'. $row, $v['name']);
            $objActSheet->setCellValueExplicit('B'. $row, $v['number']);
            $objActSheet->setCellValueExplicit('C'. $row, $v['created_at']);
            $objActSheet->setCellValueExplicit('D'. $row, $v['linkman']);
            $objActSheet->setCellValueExplicit('E'. $row, $v['address']);
            $objActSheet->setCellValueExplicit('F'. $row, $v['telephone']);
            $objActSheet->setCellValueExplicit('G'. $row, $v['created_user_name']);
            $objActSheet->setCellValueExplicit('H'. $row, $v['salesman']);
            $row++;
        }
        $objActSheet->getStyle('A2:H'. ($row-1))->applyFromArray($styleThinBlackBorderOutline);

        $fileName  = $first_row;
        $fileName .= ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}

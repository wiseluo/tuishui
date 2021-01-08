<?php

namespace App\Exports\Admin;

use App\Repositories\ProductRepository;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Border;

class ProductExport
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function productListExport($param)
    {
        $product = $this->productRepository->getProductList($param);

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
        $first_row = '产品管理';
        $param['keyword'] != '' ? $first_row .= '——关键词：'. $param['keyword'] : '';
        $objActSheet->setCellValueExplicit('A1', $first_row, PHPExcel_Cell_DataType::TYPE_STRING);

        $objActSheet->setCellValueExplicit('A2', '申请人');
        $objActSheet->setCellValueExplicit('B2', '客户');
        $objActSheet->setCellValueExplicit('C2', '产品名称');
        $objActSheet->setCellValueExplicit('D2', '产品英文名称');
        $objActSheet->setCellValueExplicit('E2', 'HS CODE');
        $objActSheet->setCellValueExplicit('F2', '规格');
        $objActSheet->setCellValueExplicit('G2', '退税率');
        $objActSheet->setCellValueExplicit('H2', '法定单位');

        $data = [];
        $product->each(function($item, $key)use(&$data){
            $data[] = [
                'created_user_name' => $item->created_user_name,
                'customer_name' => $item->customer->name,
                'name' => $item->name,
                'en_name' => $item->en_name,
                'hscode' => $item->hscode,
                'standard' => $item->standard,
                'tax_refund_rate' => $item->tax_refund_rate,
                'unit' => $item->unit,
            ];
        });

        $row = 3;
        foreach ($data as $v) {
            $objActSheet->setCellValueExplicit('A'. $row, $v['created_user_name']);
            $objActSheet->setCellValueExplicit('B'. $row, $v['customer_name']);
            $objActSheet->setCellValueExplicit('C'. $row, $v['name']);
            $objActSheet->setCellValueExplicit('D'. $row, $v['en_name']);
            $objActSheet->setCellValueExplicit('E'. $row, $v['hscode']);
            $objActSheet->setCellValueExplicit('F'. $row, $v['standard']);
            $objActSheet->setCellValueExplicit('G'. $row, $v['tax_refund_rate']);
            $objActSheet->setCellValueExplicit('H'. $row, $v['unit']);
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

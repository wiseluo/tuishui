<?php

namespace App\Exports\Admin;

use App\Repositories\TraderRepository;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Border;

class TraderExport
{
    protected $traderRepository;

    public function __construct(TraderRepository $traderRepository)
    {
        $this->traderRepository = $traderRepository;
    }

    public function traderListExport($param)
    {
        $trader = $this->traderRepository->getTraderListExportData($param);

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

        $objActSheet->mergeCells('A1:J1');
        $objActSheet->getRowDimension(1)->setRowHeight(40);
        $objActSheet->getStyle('A1')->getFont()->setBold(true);
        $objActSheet->getStyle('A1')->getFont()->setSize(20);
        $first_row = '贸易商管理';
        $param['keyword'] != '' ? $first_row .= '——关键词：'. $param['keyword'] : '';
        $objActSheet->setCellValueExplicit('A1', $first_row, PHPExcel_Cell_DataType::TYPE_STRING);

        $objActSheet->setCellValueExplicit('A2', '贸易商');
        $objActSheet->setCellValueExplicit('B2', '业务员');
        $objActSheet->setCellValueExplicit('C2', '客户名称');
        $objActSheet->setCellValueExplicit('D2', '国家');
        $objActSheet->setCellValueExplicit('E2', '地址');
        $objActSheet->setCellValueExplicit('F2', '邮箱');
        $objActSheet->setCellValueExplicit('G2', '手机');
        $objActSheet->setCellValueExplicit('H2', '网址');
        $objActSheet->setCellValueExplicit('I2', '联系人');
        $objActSheet->setCellValueExplicit('J2', '单证员');

        $data = [];
        $trader->each(function($item, $key)use(&$data){
            $data[] = [
                'name' => $item->name,
                'customer_salesman' => isset($item->customer->salesman) ? $item->customer->salesman : '',
                'customer_name' => isset($item->customer->name) ? $item->customer->name : '',
                'country_na' => isset($item->country->country_na) ? $item->country->country_na : '',
                'address' => $item->address,
                'email' => $item->email,
                'cellphone' => $item->cellphone,
                'url' => $item->url,
                'customer_linkman' => isset($item->customer->linkman) ? $item->customer->linkman : '',
                'customer_created_user_name' => isset($item->customer->created_user_name) ? $item->customer->created_user_name : '',
            ];
        });

        $row = 3;
        foreach ($data as $v) {
            $objActSheet->setCellValueExplicit('A'. $row, $v['name']);
            $objActSheet->setCellValueExplicit('B'. $row, $v['customer_salesman']);
            $objActSheet->setCellValueExplicit('C'. $row, $v['customer_name']);
            $objActSheet->setCellValueExplicit('D'. $row, $v['country_na']);
            $objActSheet->setCellValueExplicit('E'. $row, $v['address']);
            $objActSheet->setCellValueExplicit('F'. $row, $v['email']);
            $objActSheet->setCellValueExplicit('G'. $row, $v['cellphone']);
            $objActSheet->setCellValueExplicit('H'. $row, $v['url']);
            $objActSheet->setCellValueExplicit('I'. $row, $v['customer_linkman']);
            $objActSheet->setCellValueExplicit('J'. $row, $v['customer_created_user_name']);
            $row++;
        }
        $objActSheet->getStyle('A2:J'. ($row-1))->applyFromArray($styleThinBlackBorderOutline);

        $fileName  = $first_row;
        $fileName .= ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}

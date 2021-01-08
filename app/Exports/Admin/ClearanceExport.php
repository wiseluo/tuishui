<?php

namespace App\Exports\Admin;

use App\Repositories\ClearanceRepository;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Border;

class ClearanceExport
{
    protected $clearanceRepository;

    public function __construct(ClearanceRepository $clearanceRepository)
    {
        $this->clearanceRepository = $clearanceRepository;
    }

    public function clearanceListExport($param)
    {
        $clearance = $this->clearanceRepository->getClearanceList($param);

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

        $objActSheet->mergeCells('A1:V1');
        $objActSheet->getRowDimension(1)->setRowHeight(40);
        $objActSheet->getStyle('A1')->getFont()->setBold(true);
        $objActSheet->getStyle('A1')->getFont()->setSize(20);
        $first_row = '报关管理——关键词：';
        $param['keyword'] != '' ? $first_row .= $param['keyword'] : '';
        $objActSheet->setCellValueExplicit('A1', $first_row, PHPExcel_Cell_DataType::TYPE_STRING);

        $objActSheet->setCellValueExplicit('A2', '合同号');
        $objActSheet->setCellValueExplicit('B2', '客户');
        $objActSheet->setCellValueExplicit('C2', '报关详情');
        $objActSheet->setCellValueExplicit('D2', '报关单号');
        $objActSheet->setCellValueExplicit('E2', '报关日期');
        $objActSheet->setCellValueExplicit('F2', '品名');
        $objActSheet->setCellValueExplicit('G2', '数量');
        $objActSheet->setCellValueExplicit('H2', '报关单价');
        $objActSheet->setCellValueExplicit('I2', '报关金额');
        $objActSheet->setCellValueExplicit('J2', '开票工厂');
        $objActSheet->setCellValueExplicit('K2', '开票品名');
        $objActSheet->setCellValueExplicit('L2', '数量');
        $objActSheet->setCellValueExplicit('M2', '单价');
        $objActSheet->setCellValueExplicit('N2', '退税率');
        $objActSheet->setCellValueExplicit('O2', '应退税款');
        $objActSheet->setCellValueExplicit('P2', '应付退税款');
        $objActSheet->setCellValueExplicit('Q2', '目的国');
        $objActSheet->setCellValueExplicit('R2', '目的港');
        $objActSheet->setCellValueExplicit('S2', '业务员');
        $objActSheet->setCellValueExplicit('T2', '单证员');
        $objActSheet->setCellValueExplicit('U2', '箱号');
        $objActSheet->setCellValueExplicit('V2', '提单号');

        $data = [];
        $clearance->each(function($item, $key)use(&$data){
            $item->order->drawerProducts->each(function($i, $k)use(&$data, $item){
                $data[] = [
                    'ordnumber' => $item->order->ordnumber,
                    'customer_name' => isset($item->order->customer->name) ? $item->order->customer->name : '',
                    'declare_mode_str' => $item->order->declareModeData->name,
                    'customs_number' => $item->order->customs_number,
                    'customs_at' => $item->order->customs_at,
                    'pro_name' => $i->product->name,
                    'pro_number' => $i->pivot->number,
                    'clearance_single_price' => $i->pivot->single_price, //报关单价
                    'clearance_total_price' => $i->pivot->total_price, //报关金额
                    'company' => $i->drawer->company,
                    'invoice_single_price' => bcdiv($i->pivot->value, $i->pivot->number, 2), //开票单价
                    'tax_refund_rate' => $i->pivot->tax_refund_rate,
                    'refund_tax' => bcmul(bcdiv($i->pivot->value, bcadd(1, bcdiv($i->pivot->tax_rate, 100, 2), 2), 2), bcdiv($i->pivot->tax_refund_rate, 100, 2), 2), //应退税款=开票金额/(1+税率) *退税率
                    'aim_country' => $item->order->country->country_na,
                    'unloading_port' => isset($item->order->unloadingport->port_c_cod) ? $item->order->unloadingport->port_c_cod : '',
                    'salesman' => $item->order->customer->salesman,
                    'clerk' => $item->order->customer->created_user_name,
                    'box_number' => $item->order->box_number,
                    'tdnumber' => $item->order->tdnumber,
                ];
            });

        });

        $row = 3;
        foreach ($data as $v) {
            $objActSheet->setCellValueExplicit('A'. $row, $v['ordnumber']);
            $objActSheet->setCellValueExplicit('B'. $row, $v['customer_name']);
            $objActSheet->setCellValueExplicit('C'. $row, $v['declare_mode_str']);
            $objActSheet->setCellValueExplicit('D'. $row, $v['customs_number']);
            $objActSheet->setCellValueExplicit('E'. $row, $v['customs_at']);
            $objActSheet->setCellValueExplicit('F'. $row, $v['pro_name']);
            $objActSheet->setCellValueExplicit('G'. $row, $v['pro_number']);
            $objActSheet->setCellValueExplicit('H'. $row, $v['clearance_single_price']);
            $objActSheet->setCellValueExplicit('I'. $row, $v['clearance_total_price']);
            $objActSheet->setCellValueExplicit('J'. $row, $v['company']);
            $objActSheet->setCellValueExplicit('K'. $row, $v['pro_name']);
            $objActSheet->setCellValueExplicit('L'. $row, $v['pro_number']);
            $objActSheet->setCellValueExplicit('M'. $row, $v['invoice_single_price']);
            $objActSheet->setCellValueExplicit('N'. $row, $v['tax_refund_rate']);
            $objActSheet->setCellValueExplicit('O'. $row, $v['refund_tax']);
            $objActSheet->setCellValueExplicit('P'. $row, '');
            $objActSheet->setCellValueExplicit('Q'. $row, $v['aim_country']);
            $objActSheet->setCellValueExplicit('R'. $row, $v['unloading_port']);
            $objActSheet->setCellValueExplicit('S'. $row, $v['salesman']);
            $objActSheet->setCellValueExplicit('T'. $row, $v['clerk']);
            $objActSheet->setCellValueExplicit('U'. $row, $v['box_number']);
            $objActSheet->setCellValueExplicit('V'. $row, $v['tdnumber']);
            $row++;
        }
        $objActSheet->getStyle('A2:V'. ($row-1))->applyFromArray($styleThinBlackBorderOutline);

        $fileName  = $first_row;
        $fileName .= ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}

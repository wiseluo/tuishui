<?php

namespace App\Services\Member;

use App\Repositories\ClearanceRepository;

class InvoiceMaterialExcelService
{
    const TPL_PATH = 'resources/excel_tpl/invoice.xlsx';
    protected $objPHPExcel;

    public function __construct(ClearanceRepository $clearanceRepository)
    {
        $this->clearanceRepository = $clearanceRepository;
    }

    public function invoiceMaterialExport($id)
    {
        $this->objPHPExcel = \PHPExcel_IOFactory::load(base_path(static::TPL_PATH));
        $this->objPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $this->objPHPExcel->getActiveSheet();

        $clearance = $this->clearanceRepository->find($id);
        $order = $clearance->order;
        $drawerProduct = $order->drawerProducts;
        $company = $order->company;

        $objActSheet->setCellValueExplicit('C3', $company->invoice_name, \PHPExcel_Cell_DataType::TYPE_STRING);
        $objActSheet->setCellValueExplicit('C4', $company->tax_id, \PHPExcel_Cell_DataType::TYPE_STRING);
        $objActSheet->setCellValueExplicit('C5', $company->address .' '. $company->telephone, \PHPExcel_Cell_DataType::TYPE_STRING);
        $objActSheet->setCellValueExplicit('C6', $company->bankname .' '. $company->bankaccount, \PHPExcel_Cell_DataType::TYPE_STRING);
        $objActSheet->setCellValueExplicit('M1', date('Y年m月d日'), \PHPExcel_Cell_DataType::TYPE_STRING);
        $objActSheet->setCellValueExplicit('L3', $order->ordnumber, \PHPExcel_Cell_DataType::TYPE_STRING);

        if($drawerProduct){
            $i = 9;
            $drawer = ''; //外综客户只有一个开票人
            $data = $drawerProduct->map(function ($item, $key)use(&$drawer){
                $drawer = $item->drawer;
                $carry['hscode'] = $item->product->hscode;
                $carry['name'] = $item->product->name;
                $carry['number'] = $item->pivot->number;
                $carry['measure_unit'] = $item->pivot->measure_unit_cn;
                $carry['default_num'] = $item->pivot->default_num;
                $carry['default_unit'] = $item->pivot->default_unit;
                $carry['value'] = $item->pivot->value;
                $carry['standard'] = $item->pivot->standard;
                $carry['tax_refund_rate'] = $item->pivot->tax_refund_rate;
                $carry['tax_rate'] = $item->pivot->tax_rate;
                return $carry;
            });
            $amount_sum = 0;
            $tax_amount_sum = 0;
            $tax_refund_sum = 0;
            foreach ($data as $item) {
                $objActSheet->setCellValueExplicit('A'. $i, $item['name'], \PHPExcel_Cell_DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('E'. $i, $item['measure_unit'], \PHPExcel_Cell_DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('F'. $i, $item['number'], \PHPExcel_Cell_DataType::TYPE_STRING);
                //金额（不含税）=开票金额/（1+增值税率）
                $amount = bcdiv($item['value'], bcadd(1, bcdiv($item['tax_rate'], 100, 2), 2), 2);
                $amount_sum += $amount;
                //单价=金额（不含税）/数量
                $single_price = bcdiv($amount, $item['number'], 10);
                //税额=金额（不含税）*增值税率
                $tax_amount = bcmul($amount, bcdiv($item['tax_rate'], 100, 2), 2);
                $tax_amount_sum += $tax_amount;
                //退税款=金额（不含税）*退税率
                $tax_refund = bcmul($amount, bcdiv($item['tax_refund_rate'], 100, 2), 2);
                $tax_refund_sum += $tax_refund;
                $objActSheet->setCellValueExplicit('G'. $i, $single_price, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('I'. $i, $amount, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('J'. $i, $item['tax_rate'] .'%', \PHPExcel_Cell_DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('K'. $i, $tax_amount, \PHPExcel_Cell_DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('L'. $i, $item['tax_refund_rate'] .'%', \PHPExcel_Cell_DataType::TYPE_STRING);
                $objActSheet->setCellValueExplicit('M'. $i, $tax_refund, \PHPExcel_Cell_DataType::TYPE_STRING);
                $i++;
            }

            $objActSheet->setCellValueExplicit('I15', $amount_sum, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objActSheet->setCellValueExplicit('K15', $tax_amount_sum, \PHPExcel_Cell_DataType::TYPE_STRING);
            $objActSheet->setCellValueExplicit('M15', $tax_refund_sum, \PHPExcel_Cell_DataType::TYPE_STRING);
        }
        
        $objActSheet->setCellValueExplicit('D16', $this->NumToCNMoney($order->total_value_invoice,1,0), \PHPExcel_Cell_DataType::TYPE_STRING);
        $objActSheet->setCellValueExplicit('J16', $order->total_value_invoice, \PHPExcel_Cell_DataType::TYPE_STRING);

        $objActSheet->setCellValueExplicit('D17', $drawer->company, \PHPExcel_Cell_DataType::TYPE_STRING);
        $objActSheet->setCellValueExplicit('D18', $drawer->tax_id, \PHPExcel_Cell_DataType::TYPE_STRING);
        $objActSheet->setCellValueExplicit('D19', $drawer->raddress .' '. $drawer->telephone, \PHPExcel_Cell_DataType::TYPE_STRING);
        $objActSheet->setCellValueExplicit('D20', $order->customer->deposit_bank .' '. $order->customer->bank_account, \PHPExcel_Cell_DataType::TYPE_STRING);

        $objActSheet->setCellValueExplicit('C23', $company->invoice_name, \PHPExcel_Cell_DataType::TYPE_STRING);
        $objActSheet->setCellValueExplicit('C24', $company->address, \PHPExcel_Cell_DataType::TYPE_STRING);


        $filename = $clearance->order->ordnumber . $clearance->order->company->invoice_name .'开票资料';
        $this->output($filename);
    }

    public function output($filename)
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
    
        $objWriter->save('php://output');
    }

    // 阿拉伯数字转中文大写金额 NumToCNMoney(2.55,1,0)
    protected function NumToCNMoney($num,$mode = true,$sim = true){
        if(!is_numeric($num)) return '含有非数字非小数点字符！';
        $char = $sim ? array('零','一','二','三','四','五','六','七','八','九') : array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖');
        $unit  = $sim ? array('','十','百','千','','万','亿','兆') : array('','拾','佰','仟','','万','亿','兆');
        $retval = $mode ? '元':'点';
        //小数部分
        if(strpos($num, '.')){
            list($num,$dec) = explode('.', $num);
            $dec = strval(round($dec,2));
            if($mode){
                if(strlen($dec) == 0){
                    $retval .= "整";
                }elseif(strlen($dec) == 1){
                    $retval .= "{$char[$dec['0']]}角";
                }else{
                    $retval .= "{$char[$dec['0']]}角{$char[$dec['1']]}分";
                }
            }else{
                for($i = 0,$c = strlen($dec);$i < $c;$i++) {
                    $retval .= $char[$dec[$i]];
                }
            }
        }
        //整数部分
        $str = $mode ? strrev(intval($num)) : strrev($num);
        for($i = 0,$c = strlen($str);$i < $c;$i++) {
            $out[$i] = $char[$str[$i]];
            if($mode){
                $out[$i] .= $str[$i] != '0'? $unit[$i%4] : '';
                if($i>1 and $str[$i]+$str[$i-1] == 0){
                    $out[$i] = '';
                }
                if($i%4 == 0){
                    $out[$i] .= $unit[4+floor($i/4)];
                }
            }
        }
        $retval = join('',array_reverse($out)) . $retval . '整';
        return $retval;
    }
}

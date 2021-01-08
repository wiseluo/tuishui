<?php

namespace App\Services\Admin;

use App\Repositories\OrderRepository;

class CustomsOrderExcelService
{
    const TPL_PATH = 'resources/excel_tpl/customs_order.xls';
    protected $objPHPExcel;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function customsOrderExport($id)
    {
        $this->objPHPExcel = \PHPExcel_IOFactory::load(base_path(static::TPL_PATH));
        
        $order = $this->orderRepository->find($id);
        
        $this->customs($order, $this->objPHPExcel, 0);

        $filename = '报关预录入单('. $order->ordnumber .')';
        $this->output($filename);
    }
    
    //报关单
    public function customs($order, $excel, $number){
        $customs = $excel->getSheet($number);
        $drawerProduct = $order->drawerProducts;

        $cell = [
            'B3' => $order->company->customs_code." ",
            'C3' => $order->company->tax_id." ",
            'A4' => $order->company->name,
            'D4' => isset($order->clearancePortData->name) ? $order->clearancePortData->name : '',
            'F4' => $order->sailing_at,
            'H4' => $order->customs_at,
            'A6' => isset($order->trader->name) ? $order->trader->name : '',
            'D6' => $order->transPortData->name,
            'F6' => $order->ship_name,
            'H6' => $order->tdnumber,
            'C7' => $order->company->tax_id." ",
            'A8' => $order->company->name,
            'F8' => $order->company->exemption_nature,
            'A10' => $order->ordnumber,
            'D10' => isset($order->tradecountry->country_na) ? $order->tradecountry->country_na : '',
            'F10' => $order->country->country_na,
            'H10' => $order->unloadingport->port_c_cod,
            'J10' => isset($order->shipmentport->port_c_cod) ? $order->shipmentport->port_c_cod : '',
            'A12' => $order->orderPackage->name,
            'D12' => $order->total_packnum,
            'E12' => $order->total_weight,
            'F12' => $order->total_net_weight,
            'G12' => $order->priceClauseData->name,
            'H12' => $order->transport_fee,
            'I12' => $order->insurance_fee,
            'J12' => $order->miscellaneous_fee,
            'A16' => $order->box_number,
        ];
    
        if($drawerProduct){
            $index = 18;
            $data = $drawerProduct->map(function ($item, $key){
                $carry['hscode'] = $item->product->hscode;
                $carry['name'] = $item->product->name;
                $carry['number'] = $item->pivot->number;
                $carry['measure_unit'] = $item->pivot->measure_unit_cn;
                $carry['default_num'] = $item->pivot->default_num;
                $carry['default_unit'] = $item->pivot->default_unit;
                $carry['single_price'] = $item->pivot->single_price;
                $carry['total_price'] = $item->pivot->total_price;
                $carry['origin_country'] = $item->pivot->origin_country;
                $carry['destination_country'] = $item->pivot->destination_country;
                $carry['domestic_source'] = $item->pivot->domestic_source;
                $carry['constitution'] = '照章征税';
                $carry['standard'] = $item->pivot->standard;
                $carry['merge'] = $item->pivot->merge;

                $carry['value_single_price'] = bcdiv($item->pivot->value, $item->pivot->number, 2);
                $carry['value'] = $item->pivot->value;
                $carry['pack_number'] = $item->pivot->pack_number;
                $carry['total_weight'] = $item->pivot->total_weight;
                $carry['net_weight'] = $item->pivot->net_weight;
                $carry['tax_refund_rate'] = $item->pivot->tax_refund_rate;
                return $carry;
            });
            $currency = $order->currencyData->key;
            //分组合并
            $merge = [];
            foreach($data as $k => $v){
                if($v['merge'] == 0){
                    $merge[-1-$k] = $v;
                }else if($v['merge'] > 0 && array_key_exists($v['merge'], $merge)){
                    $merge[$v['merge']]['default_num'] = bcadd($merge[$v['merge']]['default_num'], $v['default_num'], 2);
                    $merge[$v['merge']]['number'] = bcadd($merge[$v['merge']]['number'], $v['number'], 2);
                    $merge[$v['merge']]['total_price'] = bcadd($merge[$v['merge']]['total_price'], $v['total_price'], 2);
                }else{
                    $merge[$v['merge']] = $v;
                }
            }
            $pi = 1;
            foreach($merge as $k =>$item){
                if($index > 20){
                    $customs->insertNewRowBefore($index, 3);
                    //$customs->getStyle("C". ($index+1))->getAlignment()->setWrapText(true);//设置单元格“自动换行”属性
                    $customs->mergeCells('C'. $index .':E'. $index);//合并
                    $customs->mergeCells('C'. ($index+1) .':E'. ($index+2));//合并
                    $customs->getStyle('A'. $index .':'.'K'.$index)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                    $customs->getStyle('A'. $index .':'.'K'.$index)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_NONE);
                    $customs->getStyle('A'. ($index+1) .':'.'K'.($index+1))->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_NONE);
                }
                $cell["A". $index] = $pi;
                $cell["B". $index] = $item['hscode'];
                $cell["C". $index] = $item['name'];
                $cell["C". ($index+1)] = $item['standard'];
                $cell["F". $index] = $item['default_num'] . $item['default_unit'];
                $cell["F". ($index+1)] = $item['number'] . $item['measure_unit'];
                $cell["G". $index] = $item['single_price'];
                $cell["G". ($index+1)] = $item['total_price'];
                $cell["G". ($index+2)] = $currency;
                $cell["H". $index] = $item['origin_country'];
                $cell["I". $index] = $item['destination_country'];
                $cell["J". $index] = $item['domestic_source'];
                $cell["K". $index] = $item['constitution'];

                $cell["L". $index] = $item['value_single_price'];
                $cell["M". $index] = $item['value'];
                $cell["N". $index] = $item['pack_number'];
                $cell["O". $index] = $item['total_weight'];
                $cell["P". $index] = $item['net_weight'];
                $cell["Q". $index] = $item['tax_refund_rate'];
                $pi += 1;
                $index+=3;
            }
        }
        
        $this->setCell($customs, $cell);
    }

    public function setCell($class, $data){
        array_walk($data, function($item, $key) use($class) {
            $class->setCellValue($key, $item);
        });
    }
    
    public function output($filename)
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .')' . '.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
    
        $objWriter->save('php://output');
    }
}

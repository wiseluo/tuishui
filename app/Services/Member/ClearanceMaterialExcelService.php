<?php

namespace App\Services\Member;

use App\Repositories\ClearanceRepository;

class ClearanceMaterialExcelService
{
    const TPL_PATH = 'resources/excel_tpl/clearance.xls';
    protected $objPHPExcel;

    public function __construct(ClearanceRepository $clearanceRepository)
    {
        $this->clearanceRepository = $clearanceRepository;
    }

    public function clearanceMaterialExport($id)
    {
        $this->objPHPExcel = \PHPExcel_IOFactory::load(base_path(static::TPL_PATH));
        
        $clearance = $this->clearanceRepository->find($id);
        
        //$this->customsSheetExcel($clearance, $this->objPHPExcel, 0);
        $this->customs($clearance, $this->objPHPExcel, 0);
        $this->invoiceExcel($clearance, $this->objPHPExcel, 1);
        $this->listExcel($clearance, $this->objPHPExcel, 2);
        $this->customsContractExcel($clearance, $this->objPHPExcel, 3);
        
        $filename = $clearance->order->ordnumber .'('. $clearance->order->company->invoice_name .')';
        $this->output($filename);
    }

    //报关单
    public function customs($clearance, $excel, $number){
        $customs = $excel->getSheet($number);
        $order = $clearance->order;
        $drawerProduct = $order->drawerProducts;
        $drawer = ''; //外综客户只有一个开票人
        $drawerProduct->map(function ($item, $key)use(&$drawer){
            $drawer = $item->drawer;
        });
        
        $cell = [
            'B3' => $order->company->customs_code." ",
            'C3' => $order->company->tax_id." ",
            'A4' => $order->company->name,
            'D4' => isset($order->clearancePortData->name) ? $order->clearancePortData->name : '',
            'F4' => $order->sailing_at,
            'H4' => $order->customs_at,
            'A6' => isset($order->receive->name) ? $order->receive->name : '',
            'D6' => $order->transPortData->name,
            'F6' => $order->ship_name,
            'H6' => $order->tdnumber,
            'B7' => $drawer->customs_code." ",
            'C7' => $drawer->tax_id." ",
            'A8' => $drawer->company,
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
            foreach($merge as $i =>$item){
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
                $pi += 1;
                $index += 3;
            }
        }
        
        $this->setCell($customs, $cell);
    }
    //报关发票
    public function invoiceExcel($clearance, $excel, $number)
    {
        $customs = $excel->getSheet($number);
        $order = $clearance->order;
        $drawerProduct = $clearance->order->drawerProducts;
        $receive_name = isset($order->receive->name) ? $order->receive->name : '';
        $receive_address = isset($order->receive->address) ? $order->receive->address : '';
        if(isset($order->unloadingport->port_e_cod) && isset($order->country->country_en)){
            $unloadingport = strtoupper($order->unloadingport->port_e_cod) . ',' . strtoupper($order->country->country_en);
        }else{
            $unloadingport = '';
        }
        $cell = [
            'A1' => $order->company->invoice_name,
            'A2' => $order->company->invoice_en_name,
            'A5' => $this->rowchr(["TO:$receive_name, $receive_address"]),
            'G6' =>  $order->ordnumber,
            'G8' =>  date('d/M/Y',strtotime($order->customs_at)),
            'B11' => isset($order->shipmentport->port_e_cod) ? strtoupper($order->shipmentport->port_e_cod) : '',
            'D11' => $unloadingport,
            'F11' => $order->transPortData->value,
        ];

        $data = $drawerProduct->map(function ($item, $key){
            $carry['en_name'] = $item->product->en_name;
            $carry['number'] = $item->pivot->number;
            $carry['unit_en'] = $item->pivot->measure_unit_en;
            $carry['single_price'] = $item->pivot->single_price;
            $carry['total_price'] = $item->pivot->total_price;
            return $carry;
        });

        $index = 16;
        $cell['A' . $index] = $order->shipping_mark;
        $sum_total = 0;
        foreach ($data as $item) {
            $cell['B' . $index] = $item['en_name'];
            $cell['D' . $index] = $item['number'];
            $cell['E' . $index] = $item['unit_en'];
            $cell['F' . $index] = $item['single_price'];
            $cell['G' . $index] = $item['total_price'];
            $sum_total = bcadd($sum_total, $item['total_price'], 2);
            $index++;
            $customs->insertNewRowBefore($index, 1);
        };
        $cell['G' . ($index+2)] = $sum_total;
        
        $this->setCell($customs, $cell);
    }
    //报关箱单
    public function listExcel($clearance, $excel, $number)
    {
        $customs = $excel->getSheet($number);
        $order = $clearance->order;
        $drawerProduct = $clearance->order->drawerProducts;
        $receive_name = isset($order->receive->name) ? $order->receive->name : '';
        $receive_address = isset($order->receive->address) ? $order->receive->address : '';
        if(isset($order->unloadingport->port_e_cod) && isset($order->country->country_en)){
            $unloadingport = strtoupper($order->unloadingport->port_e_cod) . ',' . strtoupper($order->country->country_en);
        }else{
            $unloadingport = '';
        }

        $cell = [
            'A2' => $order->company->invoice_name,
            'A3' => $order->company->invoice_en_name,
            'A6' => $this->rowchr(["TO:$receive_name, $receive_address"]),
            'G7' => $order->ordnumber,
            'G9' => date('d/M/Y',strtotime($order->customs_at)),
            'B11' => isset($order->shipmentport->port_e_cod) ? $order->shipmentport->port_e_cod : '',
            'E11' => $unloadingport,
            'H11' => $order->transPortData->value
        ];
       
        $index = 16;
        $data = $drawerProduct->map(function ($item, $key){
                $carry['en_name'] = $item->product->en_name;
                $carry['pack_number'] = $item->pivot->pack_number;
                $carry['total_weight'] = $item->pivot->total_weight;
                $carry['net_weight'] = $item->pivot->net_weight;
                $carry['volume'] = $item->pivot->volume;
                return $carry;
            });
        
        $cell['A' . $index ] = $order->shipping_mark;
        foreach ($data as $item) {
            $cell['B' . $index] = $item['en_name'];
            $cell['D' . $index] = $item['pack_number'];
            $cell['E' . $index] = isset($order->orderPackage->value) ? $order->orderPackage->value : '';
            $cell['F' . $index] = $item['total_weight'];
            $cell['G' . $index] = $item['net_weight'];
            $cell['H' . $index] = $item['volume'];
            $index++;
            $customs->insertNewRowBefore($index, 1);
        }
        $index = $index +2;
        $cell['D'. $index] = $drawerProduct->reduce(function ($carry, $item) {
            return $carry + $item->pivot->pack_number;
        }, 0);
        $cell['F'. $index] = $drawerProduct->reduce(function ($carry, $item) {
            return $carry + $item->pivot->total_weight;
        }, 0);
        $cell['G'. $index] = $drawerProduct->reduce(function ($carry, $item) {
            return $carry + $item->pivot->net_weight;
        }, 0);
        $cell['H'. $index] = $drawerProduct->reduce(function ($carry, $item) {
            return $carry + $item->pivot->volume;
        }, 0);
        $cell['E'. $index] = isset($order->orderPackage->value) ? $order->orderPackage->value : '';

        $this->setCell($customs, $cell);
    }
    //报关资料明细
    // public function customsSheetExcel($clearance, $excel, $number)
    // {
    //     $customs = $excel->getSheet($number);
    //     $order = $clearance->order;
    //     $drawerProduct = $order->drawerProducts;
    //     $receive_name = isset($order->receive->name) ? $order->receive->name : '';
    //     $receive_address = isset($order->receive->address) ? $order->receive->address : '';
    //     if(isset($order->unloadingport->port_e_cod) && isset($order->country->country_en)){
    //         $unloadingport = $order->unloadingport->port_e_cod . ',' . $order->country->country_en;
    //     }else{
    //         $unloadingport = '';
    //     }
    //     $cell = [
    //         'N4' => isset($order->sailing_at) ? $order->sailing_at : '',
    //         'B41' => 'N/M',
    //         'B46' => $this->rowchr(["TO:$receive_name, $receive_address"]),
    //         'N40' => $order->ordnumber,
    //         'N41' => date('Y/m/d', strtotime($order->customs_at)),
    //         'N42' => $unloadingport,
    //         'N43' => isset($order->shipmentport->port_e_cod) ? $order->shipmentport->port_e_cod : '',
    //         'N44' => $order->box_number,
    //         'N48' => $order->box_type,
    //         'M51' => $order->ship_name,
    //         'M52' => $order->tdnumber,
    //         'N53' => isset($order->packing_at) ? $order->packing_at : '',
    //         'C57' => isset($order->company->invoice_name) ? $order->company->invoice_name : '',
    //         'C58' => isset($order->company->invoice_en_name) ? $order->company->invoice_en_name : '',
    //         'C62' => isset($order->transPortData->key) ? $order->transPortData->key : '',
    //         'C63' => isset($order->shipmentport->port_c_cod) ? $order->shipmentport->port_c_cod : '',
    //         'K63' => isset($order->shipmentport->port_c_cod) ? $order->shipmentport->port_c_cod : '',
    //         'C64' => isset($order->shipmentport->port_e_cod) ? $order->shipmentport->port_e_cod : '',
    //         'K64' => date('Y/m/d', strtotime($order->created_at)),
    //     ];
    //     if($drawerProduct){
    //         $data = $drawerProduct->map(function ($item, $key){
    //             $carry['hscode'] = $item->product->hscode;
    //             $carry['name'] = $item->product->name;
    //             $carry['en_name'] = $item->product->en_name;
    //             $carry['number'] = $item->pivot->number;
    //             $carry['unit_en'] = $item->pivot->measure_unit_en;
    //             $carry['unit'] = $item->pivot->measure_unit_cn;
    //             $carry['single_price'] = $item->pivot->single_price;
    //             $carry['pack_number'] = isset($item->pivot->pack_number)? $item->pivot->pack_number : 0;
    //             $carry['total_weight'] = $item->pivot->total_weight;
    //             $carry['net_weight'] = $item->pivot->net_weight;
    //             $carry['volume'] = $item->pivot->volume;
    //             $carry['standard'] = $item->pivot->standard;
    //             return $carry;
    //         });

    //         $index = 8;
    //         foreach($data as $item){
    //             $cell["B". $index] = $item['hscode'];
    //             $cell["D". $index] = $item['name'];
    //             $cell["F". $index] = $item['en_name'];
    //             $cell["H". $index] = $item['number'];
    //             $cell["I". $index] = $item['unit_en'];
    //             $cell["J". $index] = $item['unit'];
    //             $cell["K". $index] = $item['single_price'];
    //             $cell["L". $index] = $item['pack_number'];
    //             $cell["M". $index] = $item['total_weight'];
    //             $cell["N". $index] = $item['net_weight'];
    //             $cell["O". $index++] = $item['volume'];
    //         }
            
    //         $index = 25;
    //         foreach($data as $value){
    //             $cell["B". $index] = $value['name'];
    //             $cell["G". $index] = $order->currencyData->key;
    //             $cell["H". $index] = '照章征税';
    //             $cell["I". $index++] = $value['standard'];
    //         }
    //         $cell['B23'] = "SAY TOTAL $order->total_packnum CTNS ONLY.";
    //     }
    //     $this->setCell($customs, $cell);
    // }
    //销售合同
    public function customsContractExcel($clearance, $excel, $number)
    {
        $customs = $excel->getSheet($number);
        $order = $clearance->order;
        $drawerProduct = $order->drawerProducts;
        $receive_name = isset($order->receive->name) ? $order->receive->name : '';
        $receive_address = isset($order->receive->address) ? $order->receive->address : '';
        $shipmentport = isset($order->shipmentport->port_e_cod) ? strtoupper($order->shipmentport->port_e_cod) : '';
        $unloadingport = isset($order->unloadingport->port_e_cod) ? strtoupper($order->unloadingport->port_e_cod) : '';
        $transPortData = isset($order->transPortData->value) ? $order->transPortData->value : '';
        
        $cell = [
            'A3' => '卖方：'. $order->company->invoice_name,
            'A4' => $order->company->invoice_en_name,
            'A6' => $this->rowchr(["TO:$receive_name, $receive_address"]),
            'F4' => $order->ordnumber,
            'F5' => date('d/M/Y', strtotime("-50 days", strtotime($order->customs_at))),
            //'F6' =>  'YIWU',
        ];
        
        if($drawerProduct){
            $index = 11;
            $data = $drawerProduct->map(function ($item, $key){
                $carry['en_name'] = $item->product->en_name;
                $carry['number'] = $item->pivot->number;
                $carry['unit_en'] = $item->pivot->measure_unit_en;
                $carry['single_price'] = $item->pivot->single_price;
                $carry['total_price'] = $item->pivot->total_price;
                return $carry;
            });
            $sum_total = 0;
            foreach($data as $item){
                $cell["A". $index] = $item['en_name'];
                $cell["C". $index] = $item['number'];
                $cell["D". $index] = $item['unit_en'];
                $cell["E". $index] = $item['single_price'];
                $cell["F". $index] = $item['total_price'];
                $sum_total = bcadd($sum_total, $item['total_price'], 2);
                $index++;
                $customs->insertNewRowBefore($index, 1);
            }
        }
        $index = $index +2;
        $cell['F'. $index] = $sum_total;
        $index = $index +3;
        $cell['B'. $index] = $order->orderPackage->value;
        $index = $index +1;
        $cell['E'. $index] = date('d/M/Y', strtotime($order->sailing_at));
        $index = $index +2;
        $cell['A'. $index] = 'FROM '. $shipmentport .' TO '. $unloadingport .' '. $transPortData;
        $index = $index +3;
        $cell['C'. $index] = $order->shipping_mark;
        $index = $index +9;
        $cell['A'. $index] = $receive_name .', '. $receive_address;
        $cell['C'. $index] = $order->company->invoice_en_name;
    
        $this->setCell($customs, $cell);
    }

    protected function rowchr(array $data)
    {
        return array_reduce($data, function($i, $item){
                return $i .=$item.chr(10);
            });
    }
    
    public function setCell($class, $data){
        array_walk($data, function($item, $key) use($class) {
            $class->setCellValue($key, $item);
        });
    }

    public function output($filename)
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
    
        $objWriter->save('php://output');
    }
}

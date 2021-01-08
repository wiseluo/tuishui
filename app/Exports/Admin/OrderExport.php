<?php

namespace App\Exports\Admin;

use App\Repositories\OrderRepository;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Border;
use App\Product;
use Illuminate\Support\Facades\DB;

class OrderExport
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function orderListExport($param)
    {
        $order = $this->orderRepository->getOrderList($param);
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

        $objActSheet->mergeCells('A1:I1');
        $objActSheet->getRowDimension(1)->setRowHeight(40);
        $objActSheet->getStyle('A1')->getFont()->setBold(true);
        $objActSheet->getStyle('A1')->getFont()->setSize(20);
        $first_row = '订单管理——关键词：';
        $param['keyword'] != '' ? $first_row .= $param['keyword'] : '';
        $objActSheet->setCellValueExplicit('A1', $first_row, PHPExcel_Cell_DataType::TYPE_STRING);

        $objActSheet->setCellValueExplicit('A2', '合同号');
        $objActSheet->setCellValueExplicit('B2', '客户');
        $objActSheet->setCellValueExplicit('C2', '报关详情');
        $objActSheet->setCellValueExplicit('D2', '订单状态');
        $objActSheet->setCellValueExplicit('E2', '报关日期');
        $objActSheet->setCellValueExplicit('F2', '国别');
        $objActSheet->setCellValueExplicit('G2', '报关金额');
        $objActSheet->setCellValueExplicit('H2', '开票工厂名称');
        $objActSheet->setCellValueExplicit('I2', '开票金额');

        $data = [];
        $order->each(function($item, $key)use(&$data){
            // 显示工厂只要一个：人民币金额最多的那一个
            $pro_value = 0;
            $company = '';
            $item->drawerProducts->each(function($i, $k)use(&$pro_value, &$company,$item,&$data){
            	//var_dump($i);
            	//exit;
//                 if($pro_value < $i->pivot->value){
//                     $pro_value = $i->pivot->value;
//                     return $company = isset($i->drawer->company) ? $i->drawer->company : '';
//                 }
            	$data[] = [
            			'ordnumber' => $item->ordnumber,
            			'customer_name' => isset($item->customer->name) ? $item->customer->name : '',
            			'declare_mode_str' => $item->declareModeData->name,
            			'status' => '已结案',
            			'customs_at' => date('Ymd', strtotime($item->customs_at)),
            			'country_na' => isset($item->trader->country->country_na) ? $item->trader->country->country_na : '',
            			'total_value' => isset($i->pivot->total_price) ? $i->pivot->total_price : '',
            			'company' => isset($i->drawer->company) ? $i->drawer->company : '',
            			'invoice_amount' => isset($i->pivot->value) ? $i->pivot->value : '',
            	];
            });

            
        });
        	
        $row = 3;
        foreach ($data as $v) {
            $objActSheet->setCellValueExplicit('A'. $row, $v['ordnumber']);
            $objActSheet->setCellValueExplicit('B'. $row, $v['customer_name']);
            $objActSheet->setCellValueExplicit('C'. $row, $v['declare_mode_str']);
            $objActSheet->setCellValueExplicit('D'. $row, $v['status']);
            $objActSheet->setCellValueExplicit('E'. $row, $v['customs_at']);
            $objActSheet->setCellValueExplicit('F'. $row, $v['country_na']);
            $objActSheet->setCellValueExplicit('G'. $row, $v['total_value']);
            $objActSheet->setCellValueExplicit('H'. $row, $v['company']);
            $objActSheet->setCellValueExplicit('I'. $row, $v['invoice_amount']);
            $row++;
        }
        $objActSheet->getStyle('A2:I'. ($row-1))->applyFromArray($styleThinBlackBorderOutline);

        $fileName  = $first_row;
        $fileName .= ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    public function orderListExport1($param)
    {
    	$order = $this->orderRepository->getOrderList($param);
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
    	
    	$objActSheet->mergeCells('A1:Y1');
    	$objActSheet->getRowDimension(1)->setRowHeight(40);
    	$objActSheet->getStyle('A1')->getFont()->setBold(true);
    	$objActSheet->getStyle('A1')->getFont()->setSize(20);
    	$first_row = '订单管理——关键词：';
    	$param['keyword'] != '' ? $first_row .= $param['keyword'] : '';
    	//$param['keyword1'] != $first_row ? $first_row .= $param['keyword1'] : '';
    	$objActSheet->setCellValueExplicit('A1', $first_row, PHPExcel_Cell_DataType::TYPE_STRING);
    	
    	$objActSheet->setCellValueExplicit('A2', '序号');
    	$objActSheet->setCellValueExplicit('B2', '出口日期');
    	$objActSheet->setCellValueExplicit('C2', '报关日期');
    	$objActSheet->setCellValueExplicit('D2', '操作');
    	$objActSheet->setCellValueExplicit('E2', '单证操作员');
    	$objActSheet->setCellValueExplicit('F2', '合同号');
    	$objActSheet->setCellValueExplicit('G2', 'HS编码');
    	$objActSheet->setCellValueExplicit('H2', '品名');
    	$objActSheet->setCellValueExplicit('I2', '退税率');
    	$objActSheet->setCellValueExplicit('J2', '报关美金');
    	$objActSheet->setCellValueExplicit('K2', '单价');
    	$objActSheet->setCellValueExplicit('L2', '数量');
    	$objActSheet->setCellValueExplicit('M2', '工厂名称');
    	$objActSheet->setCellValueExplicit('N2', '开票数量');
    	$objActSheet->setCellValueExplicit('O2', '单价');
    	$objActSheet->setCellValueExplicit('P2', '单位');
    	$objActSheet->setCellValueExplicit('Q2', '开票金额');
    	$objActSheet->setCellValueExplicit('R2', '客户名');
    	$objActSheet->setCellValueExplicit('S2', '提单号');
    	$objActSheet->setCellValueExplicit('T2', '报关单号');
    	$objActSheet->setCellValueExplicit('U2', '箱号');
    	$objActSheet->setCellValueExplicit('V2', '国家');
    	$objActSheet->setCellValueExplicit('W2', '出发港');
    	$objActSheet->setCellValueExplicit('X2', '件数');
    	$objActSheet->setCellValueExplicit('Y2', '立方数');
    	
    	
    	$data = [];
    	$order->each(function($item, $key)use(&$data){
    		$company = '';
    		$hasi=0;
    		$xlid=0;
    		$item->drawerProducts->each(function($i, $k)use(&$company,$item,&$data,&$xlid,&$hasi){
    			$bgje="";
    			if($hasi==$item->id&&$hasi>0){
    				$xlid='';
    				$hasi=$item->id;
    			}elseif($hasi!=$item->id){
    				$hasi=$item->id;
    				//$xlid=$item->id;
    				$bgje=$this->getietv($item->total_value_invoice);
    				$nfv=substr($item->created_at, 0,4);
    				$nfpx = DB::select('select count(*) as num from orders where left(created_at, 4) = ? and id<?', [$nfv,$item->id]);
    				
    				$xlid=$nfv.'-'.($nfpx[0]->num+1);
    			}
    			$ptdetail = Product::find($i->product_id);
    			$dj='';
    			if($item->total_num>0){
    				$dj=round(($item->total_value/$item->total_num),3);
    			}
    			$data[] = [
    					'a' => $xlid,//序号
    					'b' => date('Ymd', strtotime($item->export_date)),//出口日期
    					'c' => date('Ymd', strtotime($item->customs_at)),//报关日期
    					'd' => $this->getietv($item->operator),//操作
    					'e' => $this->getietv($item->created_user_name),//单证操作员
    					'f' => $item->ordnumber,//合同号
    					'g' => $ptdetail->hscode,//HS编码
    					'h' => $ptdetail->name,//品名
    					'i' => $this->getietv($i->pivot->tax_refund_rate),//退税率
    					'j' => $bgje,//报关美金
    					'k' => $dj,//单价
    					'l' => $item->total_num,//数量
    					'm' => isset($i->drawer->company) ? $i->drawer->company : '',//工厂名称
    					'n' => $this->getietv($i->pivot->number),//开票数量
    					'o' => $i->pivot->single_price,//单价
    					'p' => $this->getietv($i->pivot->measure_unit_cn),//单位
    					'q' => isset($i->pivot->value) ? $i->pivot->value : '',//开票金额
    					'r' => isset($item->customer->name) ? $item->customer->name : '',//客户名
    					's' => $this->getietv($item->tdnumber),//提单号
    					't' =>  $this->getietv($item->customs_number),//报关单号
    					'u' => $this->getietv($item->box_number),//箱号
    					'v' => isset($item->trader->country->country_na) ? $item->trader->country->country_na : '',//国家
    					'w' => $item->shipmentport->port_c_cod,//出发港
    					'x' => $this->getietv($i->pivot->number),//件数
    					'y' => $this->getietv($i->pivot->volume),//立方数
    			];
    	});
    			
    			
    });
    		
    		$row = 3;
    		foreach ($data as $v) {
    			$objActSheet->setCellValueExplicit('A'. $row, $v['a']);
    			$objActSheet->setCellValueExplicit('B'. $row, $v['b']);
    			$objActSheet->setCellValueExplicit('C'. $row, $v['c']);
    			$objActSheet->setCellValueExplicit('D'. $row, $v['d']);
    			$objActSheet->setCellValueExplicit('E'. $row, $v['e']);
    			$objActSheet->setCellValueExplicit('F'. $row, $v['f']);
    			$objActSheet->setCellValueExplicit('G'. $row, $v['g']);
    			$objActSheet->setCellValueExplicit('H'. $row, $v['h']);
    			$objActSheet->setCellValueExplicit('I'. $row, $v['i']);
    			$objActSheet->setCellValueExplicit('J'. $row, $v['j']);
    			$objActSheet->setCellValueExplicit('K'. $row, $v['k']);
    			$objActSheet->setCellValueExplicit('L'. $row, $v['l']);
    			$objActSheet->setCellValueExplicit('M'. $row, $v['m']);
    			$objActSheet->setCellValueExplicit('N'. $row, $v['n']);
    			$objActSheet->setCellValueExplicit('O'. $row, $v['o']);
    			$objActSheet->setCellValueExplicit('P'. $row, $v['p']);
    			$objActSheet->setCellValueExplicit('Q'. $row, $v['q']);
    			$objActSheet->setCellValueExplicit('R'. $row, $v['r']);
    			$objActSheet->setCellValueExplicit('S'. $row, $v['s']);
    			$objActSheet->setCellValueExplicit('T'. $row, $v['t']);
    			$objActSheet->setCellValueExplicit('U'. $row, $v['u']);
    			$objActSheet->setCellValueExplicit('V'. $row, $v['v']);
    			$objActSheet->setCellValueExplicit('W'. $row, $v['w']);
    			$objActSheet->setCellValueExplicit('X'. $row, $v['x']);
    			$objActSheet->setCellValueExplicit('Y'. $row, $v['y']);
    			$row++;
    		}
    		$objActSheet->getStyle('A2:Y'. ($row-1))->applyFromArray($styleThinBlackBorderOutline);
    		
    		$fileName  = $first_row;
    		$fileName .= ".xls";
    		header('Content-Type: application/vnd.ms-excel');
    		header("Content-Disposition: attachment;filename=\"$fileName\"");
    		header('Cache-Control: max-age=0');
    		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    		$objWriter->save('php://output');
}
public function getietv($param){
	return isset($param) ? $param : '';
}
}

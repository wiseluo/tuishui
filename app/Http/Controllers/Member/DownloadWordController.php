<?php

namespace App\Http\Controllers\Member;

use App\Repositories\ClearanceRepository;
use App\Clearance;

class DownloadWordController extends Controller
{
    
    // public function billingData(ClearanceRepository $repository, $id)
    // {
    //     $billing_path = storage_path('exports'). DIRECTORY_SEPARATOR . 'billing' . DIRECTORY_SEPARATOR;
    //     if (!is_dir($billing_path)){
    //         mkdir($billing_path, 0777);
    //     }
    //     //生成doc文件
    //     $fileArr = $this->createDoc($repository, $id);
        
    //     $save_path = storage_path('exports'). DIRECTORY_SEPARATOR . 'billing' . DIRECTORY_SEPARATOR .$id. DIRECTORY_SEPARATOR;
    //     $filename = $save_path .'billing'.$id.'.zip';//最终生成的文件名（含路径）
    //     //exec('zip -rj '. $filename .' '. $save_path);
    //     //$files = implode(' ', $fileArr);
    //     //exec('zip -j '.$filename.' '. $files);
    //     if(!file_exists($filename)){
    //         exit("无法找到文件"); //即使创建，仍有可能失败。。。。
    //     }
    //     header("Cache-Control: public");
    //     header("Content-Description: File Transfer");
    //     header('Content-disposition: attachment; filename='.basename($filename)); //文件名
    //     header("Content-Type: application/octet-stream");
    //     header("Accept-Ranges: bytes"); //告诉浏览器，这是二进制文件
    //     header('Content-Length: '. filesize($filename)); //告诉浏览器，文件大小
    //     @readfile($filename);
    // }
    //按开票工厂生成发票word文件
    public function billingData(ClearanceRepository $repository, $id)
    {
        $billing_path = storage_path('exports'). DIRECTORY_SEPARATOR . 'billing' . DIRECTORY_SEPARATOR;
        if (!is_dir($billing_path)){
            mkdir($billing_path, 0777);
        }
        $save_path = $billing_path .$id. DIRECTORY_SEPARATOR;
        if(is_dir($save_path)){
            $this->deleteAll($save_path);
        }else{
            mkdir($save_path);
        }
        $clearance = $repository->find($id);
        $company = $clearance->order->company;
        if(!$company->invoice_name){
            abort(500, '经营单位开票资料未填写');
        }
        
        //生成开票资料doc
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        //定义样式数组
        $styleTitle = array(
            'size'=>36,
            'name'=>'宋体',
        );
        $styleContent = array(
            'size'=>24,
            'name'=>'宋体',
        );
        //段落样式
        $styleCenter = array(
            'align'=>'center'
        );
        $styleLeft = array(
            'align'=>'left'
        );
        $tableStyle = array(
            'borderSize'=>3,
            'borderColor'=>'000000',
            'cellMargin'=>50,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
        );
        //水平居中
        $cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
        $cellHLeft = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT);
        //垂直居中
        $cellVCentered = array('valign' => 'center');

        $section->addText('开票资料', $styleTitle, $styleCenter);
        $section->addText('开票名称：'. $company->invoice_name, $styleContent, $styleLeft);
        $section->addText('纳税人识别号：'. $company->tax_id, $styleContent, $styleLeft);
        $section->addText('地 址：'. $company->address, $styleContent, $styleLeft);
        $section->addText('电 话：'. $company->telephone, $styleContent, $styleLeft);
        $section->addText('开户行及账号：'. $company->bankname .' '. $company->bankaccount, $styleContent, $styleLeft);
        $section->addTextBreak(1, $styleContent); // 新起一个空白段落
        $section->addText('发票收件地址：'. $company->invoice_receipt_addr, $styleContent, $styleLeft);
        $section->addText('发票收件人：'. $company->invoice_recipient, $styleContent, $styleLeft);
        $section->addText('收件人电话：'. $company->recipient_call, $styleContent, $styleLeft);
        $section->addTextBreak(1, $styleContent); // 新起一个空白段落
        $section->addText('开票内容如下：', $styleContent, $styleLeft);
        $drawerProducts = $clearance->order->drawerProducts;
        
        $phpWord->addTableStyle('myTable', $tableStyle);
        $table = $section->addTable('myTable');
        $cellWidth = 1800;
        //新一行
        $table->addRow();
        $table->addCell($cellWidth, $cellVCentered)->addText('品名', null, $cellHCentered);
        $table->addCell($cellWidth, $cellVCentered)->addText('数量', null, $cellHCentered);
        $table->addCell($cellWidth, $cellVCentered)->addText('单位', null, $cellHCentered);
        $table->addCell($cellWidth, $cellVCentered)->addText('单价', null, $cellHCentered);
        $table->addCell($cellWidth, $cellVCentered)->addText('金额', null, $cellHCentered);
        $table->addCell($cellWidth, $cellVCentered)->addText('备注', null, $cellHCentered);
        
        $total = 0;
        foreach($drawerProducts as $k => $v){
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText($v->product->name, null, $cellHCentered);
            $table->addCell($cellWidth, $cellVCentered)->addText($v->pivot->number, null, $cellHCentered);
            $table->addCell($cellWidth, $cellVCentered)->addText($v->pivot->unit, null, $cellHCentered);
            $table->addCell($cellWidth, $cellVCentered)->addText(bcdiv($v->pivot->value, $v->pivot->number, 4), null, $cellHCentered);
            $table->addCell($cellWidth, $cellVCentered)->addText(number_format($v->pivot->value, 2), null, $cellHCentered);
            $table->addCell($cellWidth, $cellVCentered)->addText('', null, $cellHCentered);
            $total += $v->pivot->value;
        }
        $table->addRow();
        $table->addCell($cellWidth, $cellVCentered)->addText('合计', null, $cellHCentered);
        $table->addCell($cellWidth, $cellVCentered)->addText('', null, $cellHCentered);
        $table->addCell($cellWidth, $cellVCentered)->addText('', null, $cellHCentered);
        $table->addCell($cellWidth, $cellVCentered)->addText('', null, $cellHCentered);
        $table->addCell($cellWidth, $cellVCentered)->addText(number_format($total, 2), null, $cellHCentered);
        $table->addCell($cellWidth, $cellVCentered)->addText('', null, $cellHCentered);

        $fileName = $clearance->order->ordnumber .$company->invoice_name .'开票资料.docx';
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition:attachment;filename=".$fileName.".docx");
        header('Cache-Control: max-age=0');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
    }

    //删除所有子目录及目录中的文件
    protected function deleteAll($path) {
        $op = dir($path);
        while(false != ($item = $op->read())) {
            if($item == '.' || $item == '..') {
                continue;
            }
            if(is_dir($op->path. DIRECTORY_SEPARATOR .$item)) {
                deleteAll($op->path. DIRECTORY_SEPARATOR .$item);
                rmdir($op->path. DIRECTORY_SEPARATOR .$item);
            } else {
                unlink($op->path. DIRECTORY_SEPARATOR .$item);
            }
        }
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

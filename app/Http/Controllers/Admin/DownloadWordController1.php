<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ClearanceRepository;

class DownloadWordController extends Controller
{
    
    public function billingData(ClearanceRepository $repository, $id)
    {
        $billing_path = storage_path('exports'). DIRECTORY_SEPARATOR . 'billing' . DIRECTORY_SEPARATOR;
        if (!is_dir($billing_path)){
            mkdir($billing_path, 0777);
        }
        //生成doc文件
        $fileArr = $this->createDoc($repository, $id);
        
        $save_path = storage_path('exports'). DIRECTORY_SEPARATOR . 'billing' . DIRECTORY_SEPARATOR .$id. DIRECTORY_SEPARATOR;
        $filename = $save_path .'billing'.$id.'.zip';//最终生成的文件名（含路径）
        exec('zip -rj '. $filename .' '. $save_path);
        //$files = implode(' ', $fileArr);
        //exec('zip -j '.$filename.' '. $files);
        if(!file_exists($filename)){
            exit("无法找到文件"); //即使创建，仍有可能失败。。。。
        }
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename='.basename($filename)); //文件名
        header("Content-Type: application/zip"); //zip格式的
        header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件
        header('Content-Length: '. filesize($filename)); //告诉浏览器，文件大小
        @readfile($filename);
    }
    //按开票工厂生成发票word文件
    public function createDoc(ClearanceRepository $repository, $id)
    {
        $save_path = storage_path('exports'). DIRECTORY_SEPARATOR . 'billing' . DIRECTORY_SEPARATOR .$id. DIRECTORY_SEPARATOR;
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
        $drawerProducts = $clearance->order->drawerProducts;
        $proArr = [];
        foreach($drawerProducts as $key => $drawerProduct){
            $proArr[$drawerProduct->drawer_id][] = $drawerProduct;
        }
        $fileArr = [];
        //循环生成购销合同doc
        foreach($proArr as $k => $v){
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            //添加页面
            $section = $phpWord->addSection();
            //定义样式数组
            $styleTitle = array(
                'bold'=>true,
                'size'=>20,
                'name'=>'宋体',
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
            //段落样式
            $styleCenter = array(
                'align'=>'center'
            );
            $section->addText($company->invoice_name, $styleTitle, $styleCenter);
            $section->addText('购销合同', $styleTitle, $styleCenter);

            $textrun1 = $section->createTextRun(array('align' => 'right'));
            $textrun1->addText('合同编号:');
            $textrun1->addText($clearance->order->number, array('underline'=>'single'));
            $textrun2 = $section->createTextRun();
            $textrun2->addText('需方：');
            $maxLength = 106;
            $textrun2->addText($company->invoice_name, array('underline'=>'single'));
            $textrun2->addText(str_repeat(' ', $maxLength - strlen($company->invoice_name)));
            $textrun2->addText('签约日期:');
            $textrun2->addText(date('Y-m-d',strtotime("-35 day")), array('underline'=>'single'));
            $textrun3 = $section->createTextRun();
            $textrun3->addText('供方：');
            $maxLength = 112;
            $textrun3->addText($v[0]->drawer->company, array('underline'=>'single'));
            $textrun3->addText(str_repeat(' ', $maxLength - strlen($v[0]->drawer->company)));
            $textrun3->addText('生效方式：');
            $textrun3->addText('盖章生效', array('underline'=>'single'));

            $section->addText('根据中华人民共和国经济合同法的有关规定,经双方协商签订本合同,以资共同信守。');

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
            foreach($v as $i => $n){
                $table->addRow();
                $table->addCell($cellWidth, $cellVCentered)->addText($n->product->name, null, $cellHCentered);
                $table->addCell($cellWidth, $cellVCentered)->addText($n->pivot->number, null, $cellHCentered);
                $table->addCell($cellWidth, $cellVCentered)->addText($n->pivot->unit, null, $cellHCentered);
                $table->addCell($cellWidth, $cellVCentered)->addText(number_format($n->pivot->value/$n->pivot->number, 4), null, $cellHCentered);
                $table->addCell($cellWidth, $cellVCentered)->addText(number_format($n->pivot->value, 2), null, $cellHCentered);
                $table->addCell($cellWidth, $cellVCentered)->addText('', null, $cellHCentered);
                $total += $n->pivot->value;
            }
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('合计', null, $cellHCentered);
            $table->addCell($cellWidth, $cellVCentered)->addText('', null, $cellHCentered);
            $table->addCell($cellWidth, $cellVCentered)->addText('', null, $cellHCentered);
            $table->addCell($cellWidth, $cellVCentered)->addText('', null, $cellHCentered);
            $table->addCell($cellWidth, $cellVCentered)->addText(number_format($total, 2), null, $cellHCentered);
            $table->addCell($cellWidth, $cellVCentered)->addText('', null, $cellHCentered);

            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('总计金额', null, $cellHCentered);
            $table->addCell($cellWidth*5, array('gridSpan' => 5, 'vMerge' => 'restart', 'valign'=>'center'))->addText('（大写）人民币'. $this->NumToCNMoney($total, 1, 0), null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('付款方式', null, $cellHCentered);
            $table->addCell($cellWidth*5, array('gridSpan' => 5, 'vMerge' => 'restart', 'valign'=>'center'))->addText('凭真实、有效、合法、16%增值税发票付款', null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('质量要求', null, $cellHCentered);
            $table->addCell($cellWidth*5, array('gridSpan' => 5, 'vMerge' => 'restart', 'valign'=>'center'))->addText('与需方确认样一致', null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('包装要求', null, $cellHCentered);
            $table->addCell($cellWidth*5, array('gridSpan' => 5, 'vMerge' => 'restart', 'valign'=>'center'))->addText('外贸标准箱包装', null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('运输条款', null, $cellHCentered);
            $table->addCell($cellWidth*5, array('gridSpan' => 5, 'vMerge' => 'restart', 'valign'=>'center'))->addText('送到义乌市苏溪镇苏福路185号，运费由供方承担', null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('违约条款', null, $cellHCentered);
            $table->addCell($cellWidth*5, array('gridSpan' => 5, 'vMerge' => 'restart', 'valign'=>'center'))->addText('双方友好协商或按经济合同法仲裁解决', null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('其他', null, $cellHCentered);
            $table->addCell($cellWidth*5, array('gridSpan' => 5, 'vMerge' => 'restart', 'valign'=>'center'))->addText('', null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('交货期', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText('', null, $cellHLeft);
            $table->addCell($cellWidth, $cellVCentered)->addText('交货地点', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText('', null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('需方名称', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText($company->invoice_name, null, $cellHLeft);
            $table->addCell($cellWidth, $cellVCentered)->addText('供方名称', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText($v[0]->drawer->company, null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('法人代表', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText($company->corporate_repre, null, $cellHLeft);
            $table->addCell($cellWidth, $cellVCentered)->addText('法人代表', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText('', null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('开户行及帐号', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText($company->bankname.$company->bankaccount, null, $cellHLeft);
            $table->addCell($cellWidth, $cellVCentered)->addText('开户行及帐号', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText('', null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('传真电话', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText($company->telephone, null, $cellHLeft);
            $table->addCell($cellWidth, $cellVCentered)->addText('传真电话', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText($v[0]->drawer->telephone, null, $cellHLeft);
            $table->addRow();
            $table->addCell($cellWidth, $cellVCentered)->addText('地址', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText($company->address, null, $cellHLeft);
            $table->addCell($cellWidth, $cellVCentered)->addText('地址', null, $cellHCentered);
            $table->addCell($cellWidth*2, array('gridSpan' => 2, 'vMerge' => 'restart', 'valign'=>'center'))->addText($v[0]->drawer->address, null, $cellHLeft);

            $section->addText('附注：本合同依法签订，即具有法律效力，双方必需全面履行，任何一方都不得私自变更或解除。因故需要变更或解除时，应双方协商一致，依法另立协议。');

            //文件命名
            $outputFileName = $v[0]->drawer->company .'购销合同.docx';

            $doc_path = $save_path . $outputFileName;
            //$doc_path = iconv("utf-8","gb2312",$doc_path);
            $fileArr[] = $doc_path;
            $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $xmlWriter->save($doc_path);
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
        $styleLeft = array(
            'align'=>'left'
        );
        $section->addText('开票资料', $styleTitle, $styleLeft);
        $section->addText('开票名称：'. $company->invoice_name, $styleContent, $styleLeft);
        $section->addText('纳税人识别号：'. $company->tax_id, $styleContent, $styleLeft);
        $section->addText('地 址：'. $company->address, $styleContent, $styleLeft);
        $section->addText('电 话：'. $company->telephone, $styleContent, $styleLeft);
        $section->addText('开户行及账号：'. $company->bankname .' '. $company->bankaccount, $styleContent, $styleLeft);
        $section->addText('发票收件地址：'. $company->invoice_receipt_addr, $styleContent, $styleLeft);
        $section->addText('发票收件人：'. $company->invoice_recipient, $styleContent, $styleLeft);
        $section->addText('收件人电话：'. $company->recipient_call, $styleContent, $styleLeft);
        $section->addTextBreak(1, $styleContent); // 新起一个空白段落
        $section->addText('温馨提示：合同盖章和发票一起寄给我，', $styleContent, $styleLeft);
        $section->addText('谢谢', $styleContent, $styleLeft);

        $outputFileName = $company->invoice_name .'开票资料.docx';

        $doc_path = $save_path . $outputFileName;
        //$doc_path = iconv("utf-8","gb2312",$doc_path);
        $fileArr[] = $doc_path;
        //dd($fileArr);
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save($doc_path);

        return $fileArr;
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

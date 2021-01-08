<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesserInterface;
use App\Customer;
use App\Order;

class UploadDealController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function upload(Request $request)
    {
        if (!$request->hasFile('fileList')) {
            return ['status'=>400, 'msg'=>'上传失败'];//
        }

        $file = $request->file('fileList');
        $extension = $file->getClientOriginalExtension(); //上传文件的后缀
        // $mimeType = $file->getMimeType(); //上传文件的类型
        $file_name = Str::random(40) .'.'. $extension;
        $path = '/'. Storage::putFileAs('images/'. date('Ymd'), $file, $file_name); //指定文件名
        return response()->json(['code'=> 200, 'msg' => $path]);
    }

    public function uploadFile(Request $request)
    {
        // $int = preg_match('/^([A-Za-z]+)(\d+)$/', 'SX180113', $arr);
        // return ['status'=>400, 'msg' => $arr, 'int'=> $int];

        if (!$request->hasFile('fileList')) {
            return ['status'=>400, 'msg'=>'上传失败'];//
        }
        $file = $request->file('fileList');
        $extension = $file->getClientOriginalExtension(); //上传文件的后缀
        // $mimeType = $file->getMimeType(); //上传文件的类型
        $file_name = Str::random(40) .'.'. $extension;
        $path = '/'. Storage::putFileAs('uploads/'. date('Ymd'), $file, $file_name); //指定文件名
        return response()->json(['code'=> 200, 'msg' => $path]);
    }

    public function uploadDeal(Request $request)
    {
        $url = $request->input('url');
        $type = $request->input('type');
        if($type == 1){
            $PHPExcel = \PHPExcel_IOFactory::load(base_path('public/'. $url));
            $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表  
            $highestRow = $sheet->getHighestRow(); // 取得总行数  
            $highestColumm = $sheet->getHighestColumn(); // 取得总列数

            /** 循环读取每个单元格的数据 */
            $dataset=array();
            for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
                $dataset[$row]['name'] = $sheet->getCell('A'.$row)->getValue();
                $BColumn = $sheet->getCell('B'.$row)->getValue();
                $dataset[$row]['cusclassify'] = ($BColumn == '自营') ? 0 : 1;
            }
            //dd($dataset);
            foreach($dataset as $k => $v){
                Customer::where('name', $v['name'])->update(['cusclassify'=> $v['cusclassify']]);
            }
            return ['status'=>200, 'msg' => '成功'];
        }else if($type == 2){
            $PHPExcel = \PHPExcel_IOFactory::load(base_path('public/'. $url));
            $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表  
            $highestRow = $sheet->getHighestRow(); // 取得总行数  
            $highestColumm = $sheet->getHighestColumn(); // 取得总列数

            /** 循环读取每个单元格的数据 */
            $dataset=array();
            for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
                $ordnumb = $sheet->getCell('A'.$row)->getValue();
                $int = preg_match('/^([A-Za-z]+)(\d+)$/', $ordnumb, $arr);
                if($int){
                    $ordnumber = $arr[1] .'-'. $arr[2];
                    $dataset[$row]['ordnumber'] = $ordnumber;
                    $dataset[$row]['customs_number'] = $sheet->getCell('B'.$row)->getValue();
                }
            }
            //dd($dataset);
            foreach($dataset as $k => $v){
                Order::where('ordnumber', $v['ordnumber'])->update(['customs_number'=> $v['customs_number']]);
            }
            return ['status'=>200, 'msg' => '成功'];
        }
        
    }

}

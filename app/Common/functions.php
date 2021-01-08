<?php

use Curl\Curl;
use Illuminate\Support\Str;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;

if (!function_exists('imgToBase64')) {
    function imgToBase64($picstr) {
        
        $picArr = explode('|', $picstr);
        return array_map(function($item) {
                $img_path = public_path($item);
                if(file_exists($img_path))
                {
                    $file = file_get_contents($img_path);
                    $base64 = base64_encode($file);
    
                    return $base64;
                }
            }, $picArr);
    }
}

if(!function_exists('countriesFunc')) {
    function countriesFunc($id) {
        return DB::table('countries')->find($id);
    }
}

if(!function_exists('districtsFunc')) {
    function districtsFunc($id) {
        return DB::table('districts')->find($id);
    }
}

if(!function_exists('unitFunc')) {
    function unitFunc($id) {
        return collect(DB::table('data')->find($id))->toArray();
    }
}

//保存erp图片到退税系统
if(!function_exists('savePicFromErpFunc')) {
    function savePicFromErpFunc($pics)
    {
        if(!$pics) {
            return '';
        }
        $picArr = explode('|', $pics);
        $imgs = array_map(function($item) {
            if(!$item) {
                return '';
            }
            $img_name = explode('.', $item);
            $count = count($img_name);
            $extension = $img_name[$count - 1];
            $contents = file_get_contents(config('app.erp_url') .'/'. $item);
            if($contents) {
                $file_name = Str::random(40) .'.'. $extension;
                $path_name = 'images/'. date('Ymd') .'/'. $file_name;
                $put_res = Storage::put($path_name, $contents);
                if($put_res) {
                    return '/'. $path_name;
                }
            }
            return '';
        }, $picArr);
        $pics = array_filter($imgs); //去空值
        $picture = implode('|', $pics);
        return $picture;
    }
}

//保存退税系统图片到erp系统
if(!function_exists('saveFileToErpFunc')) {
    function saveFileToErpFunc($pics)
    {
        if(!$pics) {
            return '';
        }
        //$img = imgToBase64($pics);
        $curl = new Curl();
        $picArr = explode('|', $pics);
        $img_arr = array_map(function($item) use($curl) {
            $img_path = public_path($item);
            if(file_exists($img_path)) {
                $file = file_get_contents($img_path);
                $base64 = base64_encode($file);

                $path_arr = explode('/', $item);
                $len = count($path_arr);
                $filename = $path_arr[$len - 1];
                $params = [
                    'name'=> $filename,
                    'file'=> $base64
                ];
                $result = $curl->post(config('app.erpapp_url').'/fileuploads',  $params)->response;
                return json_decode($result)->data;
            }
        }, $picArr);
        $pictures = implode('|', $img_arr);
        return $pictures;
    }
}

//上传文件到erp系统
if(!function_exists('uploadCurlFileToErp')) {
    function uploadCurlFileToErp($file, $param, $curl)
    {
        $mime = mime_content_type($file);
        $info = pathinfo($file);
        $name = $info['basename'];
        $curlFile = new \CURLFile($file, $mime, $name); //CURLFile不能用Curl类接口，需用原生curl_init
        $param['file'] = $curlFile;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);  //该curl_setopt可以向header写键值对
        curl_setopt($ch, CURLOPT_HEADER, 0); // 不返回头信息
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        //dd($output);
        return json_decode($output, true);
    }
}
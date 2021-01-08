<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Curl\Curl;
use Illuminate\Http\Request;

class CustomsController extends Controller
{
    public function index(Request $request)
    {
        $curl = new Curl();
        $post_data = array(
            'token'=> session('center_token'),
            'keyword'=> $request->input('keyword', ''),
            'page'=> $request->input('page', 1),
            'pageSize'=> 10
        );
        $customs = $curl->get(config('app.erpapp_url'). '/mdb/customs', $post_data)->response;
        return $customs;
    }

    public function read($id)
    {
        $curl = new Curl();
        $post_data = array(
            'token'=> session('center_token'),
        );
        $res = $curl->get(config('app.erpapp_url'). '/mdb/customs/'. $id, $post_data)->response;
        $result = json_decode($res);
        if(!isset($result->code)) {
            return response()->json(['code'=> 400, 'msg' => 'erpapp接口错误']);
        }else if(isset($result->code) && $result->code != 200){
            return response()->json(['code'=> 400, 'msg' => $result->msg]);
        }else{
            return view('admin.order.customsDetail', ['customs'=> $result->data]);
        }
    }

    public function customsImport(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['code'=> 400, 'msg' => '上传失败']);
        }

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension(); //上传文件的后缀
        $file_name = Str::random(40) .'.'. $extension;
        $path = Storage::putFileAs('images/'. date('Ymd'), $file, $file_name); //指定文件名
        $param = [
            'token' => session('center_token'),
            'type' => 'drawback',
        ];
        $curl = config('app.erpapp_url').'/mdb/customs_import';
        $result = uploadCurlFileToErp($path, $param, $curl);
        if(!isset($result['code'])) {
            return response()->json(['code'=> 400, 'msg' => 'erpapp接口错误']);
        }else if(isset($result['code']) && $result['code'] != 200){
            return response()->json(['code'=> 400, 'msg' => $result['msg']]);
        }else{
            return response()->json(['code'=> 200, 'msg' => $result['msg']]);
        }
    }

}

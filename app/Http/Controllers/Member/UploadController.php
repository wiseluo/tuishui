<?php

namespace App\Http\Controllers\Member;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesserInterface;

class UploadController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function upload(Request $request)
    {
        if (!$request->hasFile('fileList')) {
            return response()->json(['code'=> 400, 'msg' => '上传失败']);
        }

        $file = $request->file('fileList');
        $extension = $file->getClientOriginalExtension(); //上传文件的后缀
        // $mimeType = $file->getMimeType(); //上传文件的类型
        $file_name = Str::random(40) .'.'. $extension;
        $path = '/'. Storage::putFileAs('images/'. date('Ymd'), $file, $file_name); //指定文件名
        return response()->json(['code'=> 200, 'msg' => $path]);
    }

}

<?php

namespace App\Http\Controllers\Api;

use Curl\Curl;
use Illuminate\Http\Request;

class CurlController extends BaseController
{

    public function curl(Curl $curl, Request $request)
    {
        return $curl->{$request->method()}($request->url, $request->except(['url', 's']))->response;
    }
    
    public function productList(Curl $curl, Request $request)
    {
        $url = config('app.customs_url').'/public/hscode/'. request()->input('keyword');
        $res = $curl->{$request->method()}($url, ['standard'=>request()->input('standard'), 'page'=>$request->input('page')])->response;
        return response()->json(['code'=>200, 'data'=>json_decode($res)]);
    }

    public function getElement(Curl $curl, Request $request)
    {
        $url = config('app.customs_url').'/public/element/'. request()->input('hscode');
        $res = $curl->{$request->method()}($url)->response;
        return response()->json(['code'=>200, 'data'=>json_decode($res)]);
    }
}

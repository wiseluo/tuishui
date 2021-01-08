<?php

namespace App\Http\Controllers\Member;

use Curl\Curl;
use Illuminate\Http\Request;

class CurlController extends Controller
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

    //获取erp中运营管理部员工列表
    public function erpTaxUserList(Curl $curl, Request $request)
    {
        $post_data = array(
            'token'=> session('center_token'),
            'deptid'=> 55716,
        );
        $erpUsers = $curl->get(config('app.erpapp_url'). '/staffs', $post_data)->response;
        return $erpUsers;
    }

    //获取erp中收款账号列表
    public function erpEnterpriseAccount(Curl $curl, Request $request)
    {
        $post_data = array(
            'token'=> session('center_token'),
            'keyword'=> $request->input('keyword', ''),
            'page'=> $request->input('page', 1),
            'ea_isparsonal'=> $request->input('isparsonal', 0),
            'pageSize'=> 10,
        );
        $accountList = $curl->get(config('app.erpapp_url'). '/enterpriseaccount', $post_data)->response;
        return $accountList;
    }
}

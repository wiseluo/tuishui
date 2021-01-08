<?php

namespace App\Http\Controllers\Api;

use App\Repositories\UserRepository;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use App\Services\Api\LoginService;
use App\Services\AuthJWT\JWTService;
use Illuminate\Support\Facades\DB;

class LoginController extends BaseController
{

    public function login(Request $request, LoginService $loginService, UserRepository $userRepository, CustomerRepository $customerRepository)
    {
        $data = $loginService->validate($request);

        $user = $userRepository->findByWhere(['center_uid' => $data['user']->id]);
        $code = $data['code'];
        $msg = $data['msg'];
        $name = $user->name;
        $is_auth = $loginService->judgeAuth($user->id);

        $domain = $request->server('HTTP_X_FORWARDED_HOST') != '' ? $request->server('HTTP_X_FORWARDED_HOST') : $request->server('HTTP_HOST');
        //dd($domain);
        $res = DB::table('companies')->where('domain', $domain)->first();
        if($res){
            $cid = $res->id; //api端 根据域名确定cid  确定产生的数据是哪个公司的
            $customer = $customerRepository->findByWhere(['cid' => $cid, 'created_user_id' => $user->id]);
            if($customer){
                $customer_id = $customer->id;
            }else{
                $customer_id = 0;
            }
            $token = JWTService::issue($user, $cid, $customer_id);
            return response()->json(compact('token','code','msg','name','is_auth'));
        }else{
            return response()->json(['code'=>400, 'msg'=> '该域名未设定公司']);
        }
    }
}

<?php

namespace App\Services\Api;

use App\User;
use App\Personal;
use Curl\Curl;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;

class LoginService
{

    public function validate($request)
    {
        $curl = new Curl;
        //去用户中心验证
        $post_data = array(
            'username'=> $request->input('username'),
            'password'=> md5($request->input('password')),
            'action'=> 'login'
        );
		//print_r($post_data);exit;
        $res = $curl->post(config('app.user_center_url'). '/api/usercenter/api/Api.php', $post_data)->response;
       
        $result = json_decode($res);
        if($result->code == 200){
            $jwt = JWT::decode($result->token, config('app.user_center_jwt_key'), ['HS256']);
            $center_uid = $jwt->id;

            $user_res = User::where(['username'=> $request->input('username')])->first();
            if(count($user_res) == 0){
                $params = array(
                    'username'=> $request->input('username'),
                    'name'=> $request->input('username'),
                    'email'=> '',
                    'password'=> bcrypt('123456'),
                    'center_uid'=> $center_uid
                );
                $id = DB::table('users')->insertGetId($params);
            }else{
                DB::table('users')->where('id', $user_res->id)->update(['center_uid'=> $center_uid]);
            }
  
            return ['user'=>$jwt,'code'=>$result->code,'msg'=>$result->msg];
        }else{
            abort(401, $result->msg);
        }

    }

    //判断是否认证个人信息
    public function judgeAuth($id)
    {
        $personal = Personal::where([['status', '=',3], ['created_user_id', '=', $id]])->first();
        if($personal){
            return 1;
        }else{
            return 0;
        }
    } 
}

<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\RegisterApiRequest;
use Curl\Curl;
use App\Services\Api\UcenterJWT;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;

class RegisterController extends BaseController
{

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterApiRequest $request)
    {
        $this->ucenter_register($request);
        return response()->json(['code'=>'200', 'msg'=>'注册成功']);
    }

    protected function ucenter_register($request)
    {
        $user['username'] = $request->username;
        $user['password'] = md5($request->password);
        $user['phone'] = $request->input('phone', '');
        $user['email'] = '';
        $user['tid'] = 10; //退税注册客户

        $curl = new Curl;
        //去用户中心注册
        $post_data = [
            'token'=> UcenterJWT::issue($user)
        ];
        $res = $curl->post(config('app.user_center_url'). '/api/usercenter/api/Register.php', $post_data)->response;
        
        $result = json_decode($res);
        if(!$result){
            abort(401, '注册失败');
        }
        if($result->code == 200){
            $token = JWT::decode($result->token, config('app.user_center_jwt_key'), ['HS256']);

            // $user_res = User::where('username', $request->input('username'))->first();
            // if(count($user_res) == 0){

            // }else{
            //     abort(401, '该用户已存在');
            // }
            $params = array(
                'username'=> $token->username,
                'name'=> $request->name ? $request->name : $token->username,
                'email'=> '',
                'password'=> bcrypt('123456'),
                'center_uid'=> $token->id,
                'type'=> 2, //2前台客户
                'created_at'=> date('Y-m-d H:i:s')
            );
            $id = DB::table('users')->insertGetId($params);

            return $id;
        }else{
            abort(401, $result->msg);
        }
    }
}

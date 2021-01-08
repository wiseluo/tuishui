<?php

namespace App\Http\Controllers\Api;

use App\User;
use Curl\Curl;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Api\ResetPasswordApiRequest;

class ResetPasswordController extends BaseController
{

    public function resetPassword(Curl $curl, ResetPasswordApiRequest $request)
    {
        $param = $request->input();
        $userid = $this->getUserid();
        $user = User::where('id', $userid)->first();
        if($user == null){
            return response()->json(['code'=>400, 'msg'=> '账号不存在']);
        }
        // if(password_verify($param['password'], $user->password) === false){
        //     return response()->json(['code'=>400, 'msg'=> '账号密码错误']);
        // }
        // $res = $user->update(['password' => $param['newpassword']]); //触发模型修改器
        //User::where('id', $userid)->update(['password' => bcrypt($param['newpassword'])]); //不触发修改器
        // if($res){
        //     return response()->json(['code'=>200, 'msg'=> '账号密码修改成功']);
        // }else{
        //     return response()->json(['code'=>400, 'msg'=> '账号密码修改失败']);
        // }
        
        //去用户中心重置密码，本地密码不必改
        $jwt = Cache::store('file')->get($param['token']);
        $post_data = array(
            'token'=> $jwt,
            'username'=> $user->username,
            'password'=> md5($param['password']),
            'newpassword'=> md5($param['newpassword'])
        );
        //dd($post_data);
        $res_rest = $curl->post(config('app.user_center_url'). '/api/usercenter/api/RestPassword.php', $post_data)->response;
        $result = json_decode($res_rest);
        // dd($result);
        return response()->json($result);
    }
}

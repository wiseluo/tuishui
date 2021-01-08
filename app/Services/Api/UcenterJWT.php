<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Cache;
use Firebase\JWT\JWT;

class UcenterJWT
{
    //签发Token 用于用户中心注册
    public static function issue($user)
    {
        $time = time(); //当前时间
        $token = [
            'iss' => 'http://ts.judatong.com', //签发者 可选
            'aud' => 'http://ts.judatong.com', //接收该JWT的一方，可选
            'iat' => $time, //签发时间
            'nbf' => $time, //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $time+2592000, //过期时间,这里设置一个月
            "username" => $user['username'],
            "password" => $user['password'],
            "phone"    => $user['phone'],
            "email"    => $user['email'],
            "tid"       => $user['tid']
        ];
        return JWT::encode($token, config('app.user_center_jwt_key')); //输出Token
    }
    //验证token
    public static function verification($token)
    {
        JWT::$leeway = 60;//当前时间减去60，把时间留点余地
        return JWT::decode($token, config('app.user_center_jwt_key'), ['HS256']);
    }
}

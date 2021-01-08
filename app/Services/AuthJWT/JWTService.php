<?php

namespace App\Services\AuthJWT;

use Illuminate\Support\Facades\Cache;
use Firebase\JWT\JWT;
use \Firebase\JWT\SignatureInvalidException;
use \Firebase\JWT\BeforeValidException;
use \Firebase\JWT\ExpiredException;
use \UnexpectedValueException;

class JWTService
{
    //签发Token 用于api接口
    public static function issue($user, $cid, $customer_id)
    {
        $time = time(); //当前时间
        $token = [
            'iss' => 'http://ts.judatong.com', //签发者 可选
            'aud' => 'http://ts.judatong.com', //接收该JWT的一方，可选
            'iat' => $time, //签发时间
            'nbf' => $time , //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $time+2592000, //过期时间,这里设置一个月
            'data' => [ //自定义信息，不要定义敏感信息
                'id' => $user->id,
                'cid' => $cid, //api端 根据登入入口即域名确定cid，不是读取user表的cid字段
                'customer_id' => $customer_id,
                'name' => $user->name,
            ]
        ];
        $jwt = JWT::encode($token, config('app.user_center_jwt_key')); //输出Token
        $md5 = md5($jwt);
        Cache::store('file')->put($md5, $jwt, $time+86400); //一天
        return $md5;
    }

    //验证token
    public static function verification($token)
    {
        // try {
            // if(!Cache::store('file')->has($token)){
            //     throw new ExpiredException('token 已失效', 401);
            // }
            $jwt = Cache::store('file')->get($token);
            if($jwt == null){
                throw new SignatureInvalidException('token错误', 401);
            }
            JWT::$leeway = 60;//当前时间减去60，把时间留点余地
            $decoded = JWT::decode($jwt, config('app.user_center_jwt_key'), ['HS256']);
            return $decoded->data;
        // } catch(SignatureInvalidException $exception) {  //签名不正确
        //     throw new SignatureInvalidException($exception->getMessage(), 401);
        // }catch(BeforeValidException $exception) {  // 签名在某个时间点之后才能用
        //     throw new BeforeValidException($exception->getMessage(), 401);
        // }catch(ExpiredException $exception) {  // token过期
        //     throw new ExpiredException($exception->getMessage(), 401);
        // }catch(UnexpectedValueException $exception) {  // token不符合
        //     throw new UnexpectedValueException($exception->getMessage(), 401);
        // }catch(Exception $exception) {  //其他错误
        //     throw new Exception($exception->getMessage(), 401);
        // }
        //Firebase定义了多个 throw new，我们可以捕获多个catch来定义问题，catch加入自己的业务，比如token过期可以用当前Token刷新一个新Token
    }
}

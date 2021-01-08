<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Curl\Curl;
use App\User;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    //去用户中心验证
    public function ucenterlogin($curl, $post_data)
    {
        $res = $curl->post(config('app.user_center_url'). '/api/usercenter/api/Api.php', $post_data)->response;
        $result = json_decode($res);
        return $result;
    }

    //erp登录
    public function erplogin(Curl $curl, Request $request)
    {
        $post_data = array(
            'username'=> $request->input('username'),
            'password'=> $request->input('password'), //已md5加密
            'action'=> 'login'
        );
        $result = $this->ucenterlogin($curl, $post_data);
        return $this->locallogin($result, $request);
    }

    /**
     * rewrite login function, Manually authenticated users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Curl $curl, Request $request)
    {
        if($request->input('username') == 'cs001'){//管理员登录
            if ($this->guard()->attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
                $request->session()->regenerate();
                return redirect()->intended($this->redirectPath());
            }else{
                return $this->sendFailedLoginResponse($request);
            }
        }
        $post_data = array(
            'username'=> $request->input('username'),
            'password'=> md5($request->input('password')),
            'action'=> 'login'
        );
        $result = $this->ucenterlogin($curl, $post_data);
        return $this->locallogin($result, $request);
    }

    //本地登录验证
    public function locallogin($result, $request)
    {
        if($result->code == 200){
            $jwt = JWT::decode($result->token, config('app.user_center_jwt_key'), ['HS256']);
            $center_uid = $jwt->id;
            $user_res = User::where('username', $request->input('username'))->first();
            if(count($user_res) == 0){
                $params = array(
                    'username'=> $request->input('username'),
                    'name'=> $request->input('username'),
                    'email'=> '',
                    'password'=> bcrypt('123456'),
                    'center_uid'=> $center_uid,
                    'type'=> 0
                );
                $id = DB::table('users')->insertGetId($params);
                DB::table('notifications')->insert(['sender_id'=> 0, 'receiver_id'=> 1, 'content'=> '请完善账号：'. $request->input('username') .' 的信息', 'status'=> 1, 'created_at'=> date('Y-m-d H:i:s')]);
            }else{
                DB::table('users')->where('id', $user_res->id)->update(['center_uid'=> $center_uid]);
            }
        }else{
            $errors = ['username' => '用户中心账号密码错误'];
            if ($request->expectsJson()) {
                return response()->json($errors, 422);
            }
            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors($errors);
            //abort(403, 'erp账号密码错误', ['msg'=> json_encode($result->msg)]);
        }
        if ($this->guard()->attempt(['username' => $request->input('username'), 'password' => '123456', 'type'=> 0])) {
            $request->session()->regenerate(); //重新生成 Session ID
            $this->clearLoginAttempts($request);
            session(['center_token' => $result->token]);
            
            return redirect()->intended($this->redirectPath());
        }else{
            return $this->sendFailedLoginResponse($request);
        }
    }

    /**
     * 重写 Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        //$request->session()->flush();
        //$request->session()->regenerate();

        return redirect('/admin/login');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/admin';
    }
}

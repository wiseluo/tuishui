<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Notification;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user('admin'); //返回已认证的用户的实例
        if($user->role == ''){
            $confirm = false;
        }else{
            $confirm = true;
        }
        //$notifies = Notification::where(['receiver_id'=> $user->id, 'readed'=> 0])->get();
        //session(['notifies' => count($notifies)]);
        return view('admin.home', ['confirm'=> $confirm]);
    }
}

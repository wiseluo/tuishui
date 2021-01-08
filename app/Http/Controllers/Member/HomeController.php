<?php

namespace App\Http\Controllers\Member;

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
        $user = $request->user('member');
        if($user->role == ''){
            $confirm = false;
        }else{
            $confirm = true;
        }
        //$notifies = Notification::where(['receiver_id'=> $user->id, 'readed'=> 0])->get();
        //session(['notifies' => count($notifies)]);
        return view('member.home', ['confirm'=> $confirm]);
    }
}

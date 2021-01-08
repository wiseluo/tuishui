<?php

namespace App\Http\Controllers\Member;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->middleware('rbacmember');
    }

    protected $disables = ['read', 'approve'];

    public function disable(View $view)
    {
        $view->with('disabled', in_array(request()->route('type'), $this->disables) ? 'disabled' : false);
        return $view->with('readonly', in_array(request()->route('type'), $this->disables) ? 'readonly' : '');
    }

    public function getUser()
    {
        $currentGuard = Auth::getDefaultDriver();
        $user = auth($currentGuard)->user();
        return $user;
    }
    
    public function getUserCid()
    {
        $user = $this->getUser();
        return $user->cid;
    }

    public function getUserLimit()
    {
        $user = $this->getUser();
        $where[] = ['cid', '=', $user->cid]; //member端 根据user表的cid字段读取数据，api端 根据用户登入入口来确定cid(根据域名确定)读取数据
        return $where;
    }
}


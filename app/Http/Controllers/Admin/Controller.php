<?php

namespace App\Http\Controllers\Admin;

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
        $this->middleware('rbac');
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

    // public function getCidWhere()
    // {
    //     $user = $this->getUser();
    //     $where = [];
    //     if($user->username == '000001'){ //超级管理员
            
    //     }else{
    //         $where[] = ['cid', '=', $user->cid]; //member端 根据user表的cid字段读取数据，api端 根据用户登入入口来确定cid(根据域名确定)读取数据
    //     }
    //     return $where;
    // }

    public function getUserCid()
    {
        $user = $this->getUser();
        if($user->username == '000001'){ //超级管理员
            return '';
        }else{
            return $user->cid;
        }
    }

    public function getUserLimit()
    {
        $user = $this->getUser();
        $roles = [];
        $user->roles->each(function($item) use(&$roles){
            $roles[] = $item->id;
        });
        $where = [];
        if(in_array(10, $roles)){ //外聘人员 只取自己的数据
            $where[] = ['cid', '=', $user->cid];
            $where[] = ['created_user_id', '=', $user->id];
        }else{
            if($user->username == '000001'){ //超级管理员
                
            }else{
                $where[] = ['cid', '=', $user->cid]; //member端 根据user表的cid字段读取数据，api端 根据用户登入入口来确定cid(根据域名确定)读取数据
            }
        }
        return $where;
    }

    //是否是外聘人员
    public function isOutsideUser()
    {
        $user = $this->getUser();
        $roles = [];
        $user->roles->each(function($item) use(&$roles){
            $roles[] = $item->id;
        });
        $where = [];
        if(in_array(10, $roles)){ //外聘人员
            return 1;
        }else{
            return 0;
        }
    }
}


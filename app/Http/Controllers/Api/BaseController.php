<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Customer;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        
    }

    public function getBaseWhere()
    {
        $where['euid'] = request()->user->id; //外综用户id
        $where['cid'] = request()->user->cid; //所属公司id
        return $where;
    }

    public function getUserLimit()
    {
        $where[] = ['cid', '=', request()->user->cid];
        $where[] = ['created_user_id', '=', request()->user->id];
        return $where;
    }

    public function getUserid()
    {
        return request()->user->id;
    }

    public function getUserCid()
    {
        return request()->user->cid;
    }

    public function getCustomerid()
    {
        $customer = Customer::where(['cid'=> request()->user->cid, 'euid'=> request()->user->id, 'status'=> 3])->first();
        if($customer){
            return $customer->id;
        }else{
            abort(403, '客户信息未审核通过', ['msg'=> json_encode('抱歉，您的客户信息未审核通过')]);
        }
    }

    public function getUcenterid()
    {
        $id = request()->user->id;
        $user = User::find($id);
        return $user->center_uid;
    }
}

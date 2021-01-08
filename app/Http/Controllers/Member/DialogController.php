<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 14:09
 */

namespace App\Http\Controllers\Member;


class DialogController extends Controller
{
    public function __call($method, $parameters)
    {
        parent::__call($method, $parameters);
    }

    public function customer()
    {
        return view('member.dialogs.customer');
    }

    public function product()
    {
        return view('member.dialogs.product');
    }

    public function drawer_user()
    {
        return view('member.dialogs.drawer_user');
    }

    public function drawer()
    {
        return view('member.dialogs.drawer');
    }

    public function drawerOrder($id)
    {
        return view('member.dialogs.drawer_order');
    }

    public function order($id)
    {
        return view('member.dialogs.order');
    }

    public function payOrder($status)
    {
        if($status == 3){ //运费
            return view('member.dialogs.transport_order');
        }else{
            return view('member.dialogs.order');
        }
    }

    public function company()
    {
        return view('member.dialogs.company');
    }

    public function invoice()
    {
        return view('member.dialogs.invoice');
    }

    public function permission()
    {
        return view('member.dialogs.permission');
    }

    public function role()
    {
        return view('member.dialogs.role');
    }

    public function firm()
    {
        return view('member.dialogs.firm');
    }

    public function business()
    {
        return view('member.dialogs.business');
    }

    // public function payment()
    // {
    //     return view('member.dialogs.payment');
    // }

    // public function settleCompany()
    // {
    //     return view('member.dialogs.settle_company');
    // }

    public function payStore()
    {
        return view('member.dialogs.paystore');
    }

    public function drawerProduct()
    {
        return view('member.dialogs.drawer');
    }

    public function district()
    {
        return view('member.dialogs.district');
    }
}
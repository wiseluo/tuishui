<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 14:09
 */

namespace App\Http\Controllers\Admin;


class DialogController extends Controller
{
    public function __call($method, $parameters)
    {
        parent::__call($method, $parameters);
    }

    public function customer()
    {
        return view('admin.dialogs.customer');
    }

    public function product()
    {
        return view('admin.dialogs.product');
    }

    public function drawer()
    {
        return view('admin.dialogs.drawer');
    }

    public function order($id)
    {
        return view('admin.dialogs.order');
    }

    //开票工厂
    public function company()
    {
        return view('admin.dialogs.company');
    }

    public function invoice()
    {
        return view('admin.dialogs.invoice');
    }

    public function permission()
    {
        return view('admin.dialogs.permission');
    }

    public function role()
    {
        return view('admin.dialogs.role');
    }

    public function firm()
    {
        return view('admin.dialogs.firm');
    }

    public function business()
    {
        return view('admin.dialogs.business');
    }

    // public function payment()
    // {
    //     return view('admin.dialogs.payment');
    // }

    // public function settleCompany()
    // {
    //     return view('admin.dialogs.settle_company');
    // }

    // public function brand()
    // {
    //     return view('admin.dialogs.brand');
    // }

    public function district()
    {
        return view('admin.dialogs.district');
    }
}
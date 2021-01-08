<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 9:03
 */

namespace App\Observers;

use App\Settlement;
use App\Statement;
use Request;

class SettlementObserver
{
    // public function updated(Settlement $model)
    // {
    //     if ($model->status === '3') {
    //         $tax_balance = bcadd($model->order->customer->tax_balance, $model->retreat_amount, 2);
    //         $balance = bcadd($model->order->customer->balance, $tax_balance, 2);
    //         $customer_name = $model->order->customer->name;
    //         //退税款添加到客户退税款余额上
    //         $model->order->customer->tax_balance = $tax_balance;
    //         $model->order->customer->save();

    //         $params = [
    //             'customer_id'=> $model->order->customer->id,
    //             'type'=> 0,
    //             'amount'=> $model->retreat_amount,
    //             'balance'=> $balance,
    //             'content'=> '退税结算-客户：'. $customer_name .'退税余额增加rmb'. $model->retreat_amount
    //         ];
    //         $statement = New Statement($params);
    //         $statement->save();
    //     }
    // }
}
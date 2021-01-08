<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 9:03
 */

namespace App\Observers;

use App\Pay;
use App\Statement;
use Request;

class PayObserver
{
    public function creating(Pay $model)
    {
        $pay_number = 'FK'.date('Ym').'0001';
        $max_number = Pay::all()->max('pay_number');
        if($max_number){
            $month = substr($max_number, 6, 2);
            $number = substr($max_number, 8, 4);
            if(date('m') === $month){
                $i = (int)$number + 1;
                $pay_number = 'FK'.date('Ym').sprintf('%04s', (string)$i);
            }
        }
        $model->pay_number = $pay_number;
    }

    // public function updated(Pay $model)
    // {
    //     if ($model->status === '3') {

    //         if($model->money_type == 'App\Customer'){
    //             $customer_id = $model->order_id;
    //             $customer_name = $model->remit->name;
    //             $balance = bcsub($model->remit->balance, $model->money, 2);
    //             $model->remit->balance = $balance;
    //             $model->remit->save();
    //         }else if($model->money_type == 'App\Order'){
    //             $customer_id = $model->remit->customer_id;
    //             $customer_name = $model->remit->customer->name;
    //             $balance = bcsub($model->remit->customer->balance, $model->money, 2);
    //             $model->remit->customer->balance = $balance;
    //             $model->remit->customer->save();
    //         }else{
    //             $customer_id = 0;
    //             $balance = 0;
    //             $customer_name = '';
    //         }

    //         $params = [
    //             'customer_id'=> $customer_id,
    //             'type'=> 1,
    //             'amount'=> $model->money,
    //             'balance'=> $balance,
    //             'content'=> '付款管理-客户：'. $customer_name . $model->type_str .'支付rmb'. $model->money
    //         ];
    //         $statement = New Statement($params);
    //         $statement->save();
    //     }
    // }
}
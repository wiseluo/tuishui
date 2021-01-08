<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 9:03
 */

namespace App\Observers;

use App\Receipt;
use App\Statement;

class ReceiptObserver
{
    public function creating(Receipt $model)
    {
        $receipt_number = 'SH'.date('Ym').'0001';
        $max_number = Receipt::all()->max('receipt_number');
        if($max_number){
            $month = substr($max_number, 6, 2);
            $number = substr($max_number, 8, 4);
            if(date('m') === $month){
                $i = (int)$number + 1;
                $receipt_number = 'SH'.date('Ym').sprintf('%04s', (string)$i);
            }
        }
        $model->receipt_number = $receipt_number;

        //$model->rmb = bcmul($model->amount, $model->rate, 2);
    }

    // public function created(Receipt $model)
    // {
    //     $balance = bcadd($model->customer->balance, $model->rmb, 2);
    //     $model->customer->balance = $balance;
    //     $model->customer->save();

    //     $params = [
    //         'customer_id'=> $model->customer_id,
    //         'type'=> 0,
    //         'amount'=> $model->rmb,
    //         'balance'=> $balance,
    //         'content'=> '收汇管理-添加客户：'. $model->customer->name .'rmb'. $model->rmb
    //     ];
    //     $statement = New Statement($params);
    //     $statement->save();
    // }
    
    // public function deleted(Receipt $model)
    // {
    //     $balance = bcsub($model->customer->balance, $model->rmb, 2);
    //     $model->customer->balance = $balance;
    //     $model->customer->save();

    //     $params = [
    //         'customer_id'=> $model->customer_id,
    //         'type'=> 1,
    //         'amount'=> $model->rmb,
    //         'balance'=> $balance,
    //         'content'=> '收汇管理-删除客户：'. $model->customer->name .'rmb'. $model->rmb
    //     ];
    //     $statement = New Statement($params);
    //     $statement->save();
    // }

}
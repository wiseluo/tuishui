<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 9:03
 */

namespace App\Observers;

use App\Order;
use App\Data;
use App\BaseModel;
use Request;

class DataObserver
{
    protected $data_type = [
        1 => 'sales_unit',
        2 => 'clearance_port',
        3 => 'shipment_port',
        4 => 'declare_mode',
        5 => 'currency',
        6 => 'price_clause',
        7 => 'package',
        8 => 'loading_mode',
        9 => 'order_package',
        10 => 'clearance_mode',
        11 => 'business',
        16 => 'transport'
    ];
    public function deleting(BaseModel $model)
    {
        $data = Data::find(Request::input('id'));
        if(isset($this->data_type[$data->father_id])){
            $res = Order::where($this->data_type[$data->father_id], Request::input('id'))->first();
            if($res){
                abort(403, '该数据关联订单，不能删除', ['msg'=> json_encode('该数据关联订单，不能删除')]);
            }
        }
    }

}

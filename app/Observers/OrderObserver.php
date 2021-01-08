<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 9:03
 */

namespace App\Observers;

use App\Order;
use App\Settlement;
use App\Clearance;
use App\BaseModel;
use App\Pay;
use Request;

class OrderObserver
{

    public function created(BaseModel $model){
        
    }
    
    public function updating(BaseModel $model)
    {
        
    }
    
    public function deleting(BaseModel $model)
    {
        
    }

}

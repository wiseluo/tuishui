<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 9:03
 */

namespace App\Observers;

use App\BaseModel;
use Illuminate\Support\Facades\Auth;

class ModelObserver
{
    public function creating(BaseModel $model)
    {
        $currentGuard = Auth::getDefaultDriver();
        if($currentGuard == 'api'){
            $model->created_user_id = $model->updated_user_id = request()->user->id;
            $model->created_user_name = $model->updated_user_name = request()->user->name;
            $model->cid = request()->user->cid;
            
        }else if($currentGuard == 'member'){
            $model->created_user_id = $model->updated_user_id = auth($currentGuard)->user()->id;
            $model->created_user_name = $model->updated_user_name = auth($currentGuard)->user()->name;
            $model->cid = auth($currentGuard)->user()->cid;
        }else if($currentGuard == 'admin'){
            $model->created_user_id = $model->updated_user_id = auth($currentGuard)->user()->id;
            $model->created_user_name = $model->updated_user_name = auth($currentGuard)->user()->name;
            $model->cid = auth($currentGuard)->user()->cid;
        }
        
    }

    public function updating(BaseModel $model)
    {
        $currentGuard = Auth::getDefaultDriver();
        if($currentGuard == 'api'){
            $model->updated_user_id = request()->user->id;
            $model->updated_user_name = request()->user->name;
        }else if($currentGuard == 'member'){
            if(auth($currentGuard)->user()){
                $model->updated_user_id = auth($currentGuard)->user()->id;
                $model->updated_user_name = auth($currentGuard)->user()->name;
            }
        }else if($currentGuard == 'admin'){
            if(auth($currentGuard)->user()){
                $model->updated_user_id = auth($currentGuard)->user()->id;
                $model->updated_user_name = auth($currentGuard)->user()->name;
            }
        }
        
    }
}
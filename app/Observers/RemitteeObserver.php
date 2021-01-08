<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7
 * Time: 9:03
 */

namespace App\Observers;

use App\Other;
use App\Remittee;

class RemitteeObserver
{
    public function creating(Remittee $model)
    {
        $this->other($model);
    }

    public function updating(Remittee $model)
    {
        $this->other($model);
    }

    protected function other(Remittee $model)
    {
        if ($model->remit_type === Other::class) {
            $other = new Other();
            $other->name = $model->remit_id;
            $other->save();
            $model->remit_id = $other->id;
        }
    }
}
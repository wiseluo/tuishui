<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/26
 * Time: 9:08
 */

namespace App;

use App\Observers\DataObserver;

class Data extends BaseModel
{
    protected $guarded = [];

    protected $searchable = [
        'columns' => [
            'data.name' => 10,
        ]
    ];

    public static function boot()
    {
        parent::boot();

        static::observe(new DataObserver());
    }

}
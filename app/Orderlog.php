<?php

namespace App;

class Orderlog extends BaseModel
{

    protected $searchable = [
        'columns' => [
            'orderlogs.content'=> 10,
        ],
    ];

    protected $fillable = [
        'order_id',
        'ord_log',
        'pro_log',
        'ip',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

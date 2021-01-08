<?php

namespace App;

class Pzxsend extends BaseModel
{
    protected $fillable = [
        'cid',
        'order_id',
        'scmorder',
        'expcustoms',
        'purinvoice',
        'recpay',
        'taxback',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

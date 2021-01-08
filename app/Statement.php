<?php

namespace App;

class Statement extends BaseModel
{
    protected $fillable = [
        'cid',
        'customer_id',
        'customer_name',
        'style',
        'type',
        'change_amount',
        'amount',
        'deduct_amount',
        'content',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

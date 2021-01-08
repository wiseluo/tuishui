<?php

namespace App;

class Customerlog extends BaseModel
{

    protected $searchable = [
        'columns' => [
            'customerlogs.content'=> 10,
        ],
    ];

    protected $fillable = [
        'customer_id',
        'content',
        'ip',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

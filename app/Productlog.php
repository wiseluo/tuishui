<?php

namespace App;

class Productlog extends BaseModel
{

    protected $searchable = [
        'columns' => [
            'productlogs.content'=> 10,
        ],
    ];

    protected $fillable = [
        'product_id',
        'content',
        'ip',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App;

class Business extends BaseModel
{
    protected $fillable = [
        "sales_unit",
        "business",
        "contract",
        'cid',
    ];

    protected $searchable = [
        'columns' => [
            'businesses.contract' => 10,
            'data.name' => 10,
            //'data.name' => 10,
        ],
        'joins'=>[
            //'data' => ['businesses.sales_unit','data.id'],
            'data' => ['businesses.business','data.id'],
        ]
    ];

    public function salesUnit()
    {
        return $this->belongsTo(Data::class, 'sales_unit');
    }

    public function business()
    {
        return $this->belongsTo(Data::class, 'business');
    }
}

<?php

namespace App;

class Link extends BaseModel
{
    protected $searchable = [
        'columns' => [
            'links.name' => 10,
        ],
    ];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'country',
        'address',
        'cid',
    ];
}

<?php

namespace App;

use App\Http\Traits\Status;

class Receive extends BaseModel
{
    use Status;

    protected $searchable = [
        'columns' => [
            'receive.name' => 10,
        ],
    ];

    protected $fillable = [
        'name',
        'country',
        'address',
        'aeocode',
        'fax',
        'web',
        'phone',
        'cid',
    ];
}

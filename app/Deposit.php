<?php

namespace App;

use App\Http\Traits\Status;

class Deposit extends BaseModel
{
    use Status;

    protected $searchable = [
        'columns' => [
            'deposit.name' => 10,
        ],
    ];

    protected $fillable = [
        'name',
        'address',
        'phone',
        'cid',
    ];
}

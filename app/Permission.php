<?php

namespace App;


use Nicolaslopezj\Searchable\SearchableTrait;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'permissions.name' => 10,
            'permissions.display_name' => 9,
            'permissions.description' => 8
        ],
    ];

    protected $fillable = [
    	'display_name',
    	'name',
    	'description',
        'ismenu',
    ];

}

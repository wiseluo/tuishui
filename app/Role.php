<?php

namespace App;

use Nicolaslopezj\Searchable\SearchableTrait;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'roles.name' => 10,
            'roles.display_name' => 9,
            'roles.description' => 8
        ],
    ];

    protected $fillable = [
    	'display_name',
    	'name',
    	'description'
    ];

}

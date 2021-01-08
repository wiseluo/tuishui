<?php

namespace App;

class Trader extends BaseModel
{
    protected $fillable = [
    	"name",
        "customer_id",
        "country_id",
    	"address",
    	"email",
    	"cellphone",
    	"url",
        'cid',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}

<?php

namespace App;

class Port extends BaseModel
{
    protected $fillable = [
        'port_c_cod',
        'port_e_cod',
        'port_count',
        'port_code',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_co', 'port_count' );
    }
}

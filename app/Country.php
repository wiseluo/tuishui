<?php

namespace App;

class Country extends BaseModel
{
    protected $fillable = [
        'country_co',
        'country_en',
        'country_na',
        'exam_mark',
        'high_low',
    ];

    public function ports()
    {
        return $this->hasMany(Port::class, 'port_count' ,'country_co');
    }
}

<?php

namespace App;

use App\Observers\RemitteeObserver;

class Remittee extends BaseModel
{
    protected $fillable = [
        'cid',
        'status',
        'remit_type',
        'remit_id',
        'name',
        'number',
        'bank',
    ];

    protected $appends = ['remit_type_str', 'tag'];

    const TYPE = [Customer::class=>'客户', Drawer::class=>'开票人', Other::class=>'其他'];

    public static function boot()
    {
        parent::boot();

        static::observe(new RemitteeObserver());
    }

    public function getRemitTypeStrAttribute()
    {
        return array_get(static::TYPE, $this->getAttribute('remit_type'), '未知');
    }

    public function remit()
    {
        return $this->morphTo();
    }

    const TAG = [
        Customer::class => 'name',
        Drawer::class => 'company',
        Other::class => 'name'
    ];

    public function getTagAttribute()
    {
        return $this->remit->{array_get(static::TAG, $this->getAttribute('remit_type'))};
    }
    
    public function scopeOfType($query, $type)
    {
        if(!empty($type)){
            return $query->where('remit_type', $type);
        }else{
            return $query;
        }
        
    }
}

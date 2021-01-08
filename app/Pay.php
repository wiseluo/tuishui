<?php

namespace App;

use App\Http\Traits\Status;
use App\Observers\PayObserver;

class Pay extends BaseModel
{
    use Status;

    protected $fillable = [
        'cid',
        'status',
        'applied_at',
        'type',
        'customer_id',
        'company_id',
        'remittee_id',
        'money',
        'content',
        'picture',
        'opinion',
        'pay_at',
        'pay_number',
        'money_type',
        'binded',
        'operator_id',
        'approved_at',
        'fid',
        'yw_bills',
        'yw_public',
        'dj_count',
        'yw_applyid',
        'yw_billpics',
        'apply_uid',
        'apply_uname',
        'business_type',
    ];

    const STATUS = [
        0=>'所有',
        1=>'草稿',
        2=>'审批中',
        3=>'审批通过',
        4=>'审批拒绝',
    ];

    protected $searchable = [
        'columns' => [
            'pays.type' => 10,
            'remittees.name' => 10,
            'customers.name' => 10,
            'customers.salesman' => 10,
        ],
        'joins' => [
            'remittees' => ['pays.remittee_id','remittees.id'],
            'customers' => ['pays.customer_id','customers.id'],
        ],
    ];
    
    protected $appends = [
        'type_str'
    ];
    
    const TYPE = [
        0 => '定金',
        1 => '货款',
        2 => '税款',
        3 => '运费',
        4 => '其它',
    ];
    
    public static function boot()
    {
        parent::boot();
        
        static::observe(new PayObserver());
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function remittee()
    {
        return $this->belongsTo(Remittee::class);
    }

    public function order()
    {
        return $this->belongsToMany(Order::class)->withPivot(['money'])->withTimestamps();
    }

    public function receipt()
    {
        return $this->belongsToMany(Receipt::class)
            ->withPivot([
                'pay_money',
                'real_receipt_date',
                'real_receipt_money',
            ])->withTimestamps();
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }
    
    public function getTypeStrAttribute()
    {
        return self::TYPE[$this->type];
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
}

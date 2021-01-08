<?php

namespace App;

use App\Http\Traits\Status;
use App\Observers\ReceiptObserver;

class Receipt extends BaseModel
{
    use Status;
    
    protected $fillable = [
        'cid',
        'status',
        'receipt_number',
        'customer_id',
        'receiptcorp',
        'receiptcorpid',
        'account',
        'account_id',
        'bank',
        'currency_id',
        'advance_amount',
        'received_at',
        'rate',
        'amount',
        'rmb',
        'payer',
        'picture',
        'sk_applyid',
        'apply_uid',
        'apply_uname',
        'remark',
        'country_id',
        'business_type',
        'exchange_type',
        'expected_received_at',
    ];

    protected $appends = ['receipted_amount', 'relate_str', 'status_str'];

    const STATUS = [
        0=>'所有',
        1=>'草稿',
        2=>'审批中',
        3=>'审批通过',
        4=>'审批拒绝',
        5=>'已结汇',
    ];

    protected $searchable = [
        'columns' => [
            'customers.name' => 10,
            'customers.salesman' => 10,
            'customers.created_user_name' => 10,
        ],
        'joins'=>[
            'customers' => ['customers.id','receipts.customer_id'],
        ]
    ];

    public static function boot()
    {
        parent::boot();

        static::observe(new ReceiptObserver());
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function currency()
    {
        return $this->belongsTo(Data::class, 'currency_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot(['money', 'id'])->withTimestamps();
    }

    public function getReceiptedAmountAttribute()
    {
        return $this->orders()->sum('order_receipt.money');
    }

    /*
            1）未关联：   该收汇单还未关联任何订单  待关联金额=收汇金额
            2）部分关联： 该收汇单已关联部分订单，待关联金额 > 0
            3）已关联:     该收汇单金额已全部关联订单，待关联金额为0
    */

    public function getRelateStrAttribute()
    {
        if (!$this->receipted_amount) {
            return '未关联';
        }

        if ($this->receipted_amount == $this->getAttribute('amount')) {
            return '已关联';
        }

        return '部分关联';
    }
    
}

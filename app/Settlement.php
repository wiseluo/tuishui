<?php

namespace App;
use App\Http\Traits\Status;
use App\Observers\SettlementObserver;

class Settlement extends BaseModel
{
    use Status;
    
    protected $searchable = [
        'columns' => [
            'orders.ordnumber' => 10,
        ],
        'joins' => [
            'orders' => ['settlements.order_id','orders.id'],
        ],
    ];

    protected $fillable = [
        'order_id',
        'user_id',
        'total_value',
        'currency',
        'invoice_sum',
        'paid_deposit_sum',
        'paid_payment_sum',
        'refund_tax_sum',
        'commission_sum',
        'payable_refund_tax_sum',
        'settle_at',
        'opinion',
        'approved_at',
        'status',
        'cid',
    ];
    
    const STATUS = [
        0=>'所有',
        1=>'待结算',
        2=>'审批中',
        3=>'已结算',
        4=>'审批拒绝',
    ];
    
    protected $appends = [
        'status_str',
    ];

    public static function boot()
    {
        parent::boot();
        
        static::observe(new SettlementObserver());
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function settlepayment()
    {
        return $this->hasMany(Settlepayment::class);
    }

    public function currencyData()
    {
        return $this->belongsTo(Data::class, 'currency');
    }

}

<?php

namespace App;

use App\Http\Traits\Status;

class Invoice extends BaseModel
{
    use Status;

    protected $fillable = [
        'status',
        'drawer_id',
        'order_id',
        'number',
        'invoice_amount',
        'billed_at',
        'received_at',
        'approved_at',
        'opinion',
        'cid',
        'erp_id',
        'created_user_id',
        'created_user_name',
    ];

    protected $appends = ['status_str', 'tax_amount_sum'];

    const STATUS = [
        0=>'所有',
        1=>'草稿',
        2=>'审批中',
        3=>'待申报',
        4=>'审批拒绝',
        5=>'已申报',
        6=>'已退税',
    ];

    public function drawerProductOrders()
    {
        return $this->belongsToMany(DrawerProductOrder::class)
                    ->withPivot([
                        'id',
                        'tax_rate',
                        'single_price',
                        'quantity',
                        'amount',
                        'refund_tax_amount',
                        'product_untaxed_amount',
                        'product_tax_amount',
                    ]);
    }

    //应退税款之和
    public function getTaxAmountSumAttribute()
    {
        return $this->drawerProductOrders()->sum('refund_tax_amount');
    }

    public function filing()
    {
        return $this->belongsTo(Filing::class);
    }

    public function drawer()
    {
        return $this->belongsTo(Drawer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}

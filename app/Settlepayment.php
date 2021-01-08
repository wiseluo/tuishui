<?php

namespace App;

class Settlepayment extends BaseModel
{
    protected $fillable = [
        'settlement_id',
        'drawer_id',
        'company',
        'invoice_amount',
        'received_invoice',
        'paid_deposit',
        'paid_payment',
        'refund_tax',
        'commission',
        'payable_refund_tax',
        'cid',
    ];

    public function settlement()
    {
        return $this->belongsTo(Settlement::class);
    }

    public function drawer()
    {
        return $this->belongsTo(Drawer::class);
    }
}

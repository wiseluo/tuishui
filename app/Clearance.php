<?php

namespace App;

class Clearance extends BaseModel
{
    protected $fillable = [
        'cid',
        'status',
        'order_id',
        'generator',
        'prerecord',
        'declare',
        'release',
        'lading',
        'commercial_invoice',
        'packing_List',
        'transport',
        'inventory',
        'purchase_sale_contract',
        'payment_agreement',
        'other_info',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // public function getProductCountAttribute()
    // {
    //     $drawerproductinvoices =  DrawerProductOrderInvoice::where('clearance_id', $this->id)
    //         ->groupBy('drawer_product_order_id')
    //         ->selectRaw('SUM(amount) AS sum_amount, SUM(quantity) as sum_quantity, drawer_product_order_id')
    //         ->get()->toArray();

    //     return array_reduce($drawerproductinvoices, function($ret, $value) {
    //         $ret[array_get($value, 'drawer_product_order_id')] = $value;
    //         return $ret;
    //     },[]);
    // }

}

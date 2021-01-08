<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrawerProductOrderInvoice extends Model
{
    protected $table = 'drawer_product_order_invoice';

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function drawerProductOrder()
    {
        return $this->belongsTo(DrawerProductOrder::class);
    }

}
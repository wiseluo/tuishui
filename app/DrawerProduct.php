<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrawerProduct extends Model
{
    protected $table = 'drawer_product';
    protected $fillable = [
        'product_id',
        'drawer_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function drawer()
    {
        return $this->belongsTo(Drawer::class);
    }

    public function order()
    {
        return $this->belongsToMany(Order::class)->withTimestamps();
    }
}

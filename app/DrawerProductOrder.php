<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrawerProductOrder extends Model
{
    protected $table = 'drawer_product_order';
    protected $fillable = [
        'id',
        'order_id',
        'drawer_product_id',
        'standard',
        'company',
        'number',
        'unit',
        'single_price',
        'default_num',
        'default_unit_id',
        'default_unit',
        'value',
        'volume',
        'net_weight',
        'total_weight',
        'measure_unit',
        'measure_unit_cn',
        'measure_unit_en',
        'pack_number',
        'total_price',
        'merge',
        'domestic_source_id',
        'domestic_source',
        'production_place',
        'origin_country_id',
        'origin_country',
        'destination_country_id',
        'destination_country',
        'goods_attribute',
        'ciq_code',
        'species',
        'surface_material',
        'section_number',
        'enjoy_offer',
        'exchange_cost',
        'tax_refund_rate',
        'tax_rate',
        'invoice_quantity',
        'invoice_amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function drawerProduct()
    {
        return $this->belongsTo(DrawerProduct::class);
    }

    public function invoice()
    {
        return $this->belongsToMany(Invoice::class)
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
}
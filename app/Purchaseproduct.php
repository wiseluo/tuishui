<?php

namespace App;

class Purchaseproduct extends BaseModel
{
    protected $searchable = [
        'columns' => [
            'purchaseproducts.name' => 10,
        ],
    ];

    protected $fillable = [
        'id',
        'order_pro_id',
        'purchase_id',
        'name',
        'pro_number',
        'class_id',
        'class_name',
        'supplier_id',
        'supplier',
        'package',
        'material',
        'color',
        'size',
        'unit',
        'quantity',
        'box_quantity',
        'total_quantity',
        'price',
        'total_price',
        'volume',
        'total_volume',
        'weight',
        'total_weight',
        'img',
        'about',
    ];

}

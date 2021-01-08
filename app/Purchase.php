<?php

namespace App;

class Purchase extends BaseModel
{
    protected $searchable = [
        'columns' => [
            'purchases.purchase_code' => 10,
            'purchases.cus_name' => 10,
            'purchases.buyer' => 10,
        ],
    ];

    protected $fillable = [
        'id',
        'order_id',
        'export_id',
        'export_code',
        'prefix_code',
        'purchase_code',
        'sign_date',
        'delivery_date',
        'supplier_id',
        'supplier',
        'supplier_addr',
        'deposit',
        'buyer_id',
        'buyer',
        'salesman_id',
        'salesman',
        'cus_id',
        'cus_name',
        'status',
        'create_time',
        'finish_date',
        'payment_time',
        'link_id',
        'link_name',
        'link_phone',
        'link_qq',
        'bank_account',
        'bank_number',
        'bank_deposit',
        'about',
        'pro_description',
        'sum_price',
        'business_license',
        'sales_invoice',
        'receipts_invoice',
        'factory_brand',
        'production_line',
        'water_ele_invoice',
        'vat_declaration_form',
        'opinion',
        'approved_at',
        'refundable',
    ];

}
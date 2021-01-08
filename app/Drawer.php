<?php

namespace App;

use App\Http\Traits\Status;

class Drawer extends BaseModel
{
    use Status;

    protected $fillable = [
        'cid',
        'status',
        'euid',
        'customer_id',
        'company',
        'telephone',
        'phone',
        'purchaser',
        'tax_id',
        'licence',
        'address',
        'domestic_source_id',
        'domestic_source',
        'pic_register',
        'pic_verification',
        'pic_production',
        'pic_brand',
        'pic_other',
        'approved_at',
        'opinion',
        'tax_at',
        'addressee',
        'raddress',
        'tax_rate',
        'export',
        'customs_code',
        'pic_home',
        'pic_business_license',
        'pic_receipts_income',
        'tax_return',
        'pic_credit_rating',
        'pic_warehouse_photos',
        'tax_customs_code',
        'pic_customs_certificate',
        'pic_sales_invoice',
    	'busines_scope',
        'corporate_repre',
        'bankname',
        'bankaccount',
    ];

    protected $appends = ['status_str'];

    const STATUS = [
        0=>'所有',
        1=>'草稿',
        2=>'审批中',
        3=>'审批通过',
        4=>'审批拒绝',
    ];

    protected $searchable = [
        'columns' => [
            'drawers.company' => 10,
            'drawers.tax_id' => 10,
            'customers.name' => 10,
            'customers.salesman' => 10,
            'customers.created_user_name' => 10,
        ],
        'joins' => [
            'customers' => ['drawers.customer_id','customers.id'],
        ],
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    public function remittees()
    {
        return $this->morphMany(Remittee::class, 'remit');
    }
}

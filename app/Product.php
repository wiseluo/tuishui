<?php

namespace App;

use App\Http\Traits\Status;

class Product extends BaseModel
{
    use Status;

    protected $searchable = [
        'columns' => [
            'products.name' => 10,
            'products.en_name' => 10,
            'products.created_user_name' => 10,
            'products.hscode' => 10,
            'products.standard' => 10,
            'customers.salesman' => 10,
            'customers.name' => 10,
        ],
        'joins' => [
            'customers' => ['products.customer_id', 'customers.id'],
        ],
    ];

    protected $fillable = [
        'name',
        'customer_id',
        'en_name',
        'hscode',
        'standard',
        'unit',
        'number',
        'tax_refund_rate',
        'picture',
        'status',
        'remark',
        'opinion',
        'approved_at',
        'cid',
        'brand_id',
        'customs_img',
        'appearance_img',
        'brand_img',
        'pack_img',
        'other_img',
        'link_id',
        'measure_unit',
    ];

    protected $appends = [
        'status_str',
        'picture_str',
    ];

    const STATUS = [
        0=>'所有',
        1=>'草稿',
        2=>'审批中',
        3=>'审批通过',
        4=>'审批拒绝',
    ];

    public function getPictureStrAttribute()
    {
        return array_get($this->attributes, 'picture') ? explode('|', array_get($this->attributes, 'picture'))[0] : '';
    }
    
    public function drawer()
    {
        return $this->belongsToMany(Drawer::class)->withTimestamps();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function link()
    {
        return $this->belongsTo(Link::class);
    }

    public function measureUnitData()
    {
        return $this->belongsTo(Data::class, 'measure_unit');
    }
    
    //品牌
    // public function getBrandStrAttribute()
    // {
    //     return $this->getAttribute('brand_id') ? Brand::find($this->getAttribute('brand_id'))->name : null;
    // }

}

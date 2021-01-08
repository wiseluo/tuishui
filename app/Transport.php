<?php

namespace App;

use App\Http\Traits\Status;

class Transport extends BaseModel
{
    use Status;

    protected $fillable = [
        'cid',
        'status',
        'erp_id',
        'company_id',
        'name',
        'number',
        'money',
        'applied_at',
        'billed_at',
        'picture',
    ];


    const STATUS = [
        //1=>'草稿',
        2=>'审批中',
        3=>'审批通过',
        4=>'审批拒绝',
    ];

    protected $appends = ['status_str'];

    //开票公司
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function order()
    {
        return $this->belongsToMany(Order::class)
            ->withPivot([
                'order_id',
                'transport_id',
                'money',
            ])->withTimestamps();
    }

}

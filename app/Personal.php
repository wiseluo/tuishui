<?php

namespace App;

use App\Http\Traits\Status;

class Personal extends BaseModel
{
    use Status;

    protected $searchable = [
        'columns' => [
            'personals.drawer_name' => 10,
        ],
    ];

    protected $fillable = [
        'drawer_name',
        'legal_person',
        'phone',
        'tax_id',
        'tax_at',
        'original_addr',
        'pic_invoice',
        'pic_verification',
        'pic_register',
        'cid',
        'status',
        'approved_at',
        'opinion',
        'service_agreement_pic',
        'tax_refund_agreement_pic',
        'ucenterid',
        'remark',
    ];
    
    const STATUS = [
        0=>'所有',
        1=>'草稿',
        2=>'审批中',
        3=>'审批通过',
        4=>'审批拒绝',
    ];

    public function getStatusStrAttribute()
    {
        return  self::STATUS[$this->getAttribute('status')];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }
}

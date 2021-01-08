<?php

namespace App;

use App\Http\Traits\Status;

class Customer extends BaseModel
{
    use Status;
    protected $searchable = [
        'columns' => [
            'customers.name' => 10,
            'customers.salesman' => 10,
            'customers.created_user_name'=> 10,
        ],
    ];

    protected $fillable = [
        'cid',
        'status',
        'euid',
        'name',
        'linkman',
        'password',
        'address',
        'telephone',
        'number',
        'service_rate',
        'receiver',
        'deposit_bank',
        'bank_account',
        'u_name',
        'salesman',
        'picture_lic',
        'picture_pact',
        'picture_other',
        'approved_at',
        'opinion',
        'custype',
        'card_type',
        'card_num',
        'line_credit',
        'email',
        'balance',
        'tax_balance',
        'card_name',
        'usc_code',
        'reg_capital',
        'set_date',
        'legal_name',
        'legal_num',
        'bill_base',
        'cusclassify',
        'ucenterid',
	    'province_code',
	    'city_code',
	    'district_code',
    ];
    
    const STATUS = [
        0=>'所有',
        2=>'审批中',
        3=>'审批通过',
        4=>'审批拒绝',
    ];

    public function getAllbalanceAttribute()
    {
        return bcadd($this->getAttribute('balance'), $this->getAttribute('tax_balance'), 2);
    }

}

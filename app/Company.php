<?php

namespace App;

use App\Http\Traits\Status;

class Company extends BaseModel
{
    use Status;

    protected $searchable = [
        'columns' => [
            'companies.name' => 10,
        ],
    ];

    protected $fillable = [
        'cid',
        'name',
        'customs_code',
        'tax_id',
        'prefix',
        'domain',
        'invoice_name',
        'invoice_en_name',
        'corporate_repre',
        'address',
        'telephone',
        'bankname',
        'bankaccount',
        'invoice_receipt_addr',
        'invoice_recipient',
        'recipient_call',
        'erp_companyid',
        'exemption_nature',
    	'exemption_code',
    ];


}

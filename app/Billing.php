<?php

namespace App;

class Billing extends BaseModel
{
    protected $fillable = [
        'data_id',
        'name',
        'identification_num',
        'corporate_repre',
        'address',
        'telephone',
        'bankname',
        'bankaccount',
        'invoice_receipt_addr',
        'invoice_recipient',
        'recipient_call',
        'customs_code',
        'cid',
    ];
    protected $searchable = [
        'columns' => [
            'billings.name' => 10
        ]
    ];

    public function data()
    {
        return $this->belongsTo(Data::class);
    }
}

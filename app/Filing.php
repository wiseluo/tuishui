<?php

namespace App;

use App\Http\Traits\Status;

class Filing extends BaseModel
{
    use Status;

    protected $fillable = [
        'cid',
        'status',
        'applied_at',
        'batch',
        'declared_amount',
        'amount',
        'invoice_quantity',
        'returned_at',
        'letter',
        'erp_id',
        'entry_person',
        'created_user_id',
        'created_user_name',
    ];

    protected $searchable = [
        'columns' => [
            'filings.batch' => 10,
            'filings.applied_at' => 10,
            'filings.returned_at' => 10,
            'filings.amount' => 10,
        ],
        'joins' => [
            
        ],
    ];
    
    protected $appends = ['status_str'];

    const STATUS = [
        0=>'所有',
        1=>'待退税',
        2=>'已退税',
    ];

    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }

}
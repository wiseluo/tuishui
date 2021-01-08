<?php

namespace App;

use App\Http\Traits\Status;

class Brand extends BaseModel
{
    use Status;

    protected $searchable = [
        'columns' => [
            'brands.name' => 10,
        ],
    ];

    protected $fillable = [
        'name',
        'type',
        'logo_img',
        'link_id',
        'auth_img',
        'status',
        'opinion',
        'approved_at',
        'cid',
        'classify',
    ];
    
    protected $appends = [
        'type_str',
        'classify_str',
        'status_str',
    ];
    
    const STATUS = [
        0=>'所有',
        1=>'草稿',
        2=>'审批中',
        3=>'审批通过',
        4=>'审批拒绝',
    ];
    
    public function link() {
        return $this->belongsTo(Link::class);
    }
    
    public function getTypeStrAttribute()
    {
        return $this->getAttribute('type') ? Data::find($this->getAttribute('type'))->name : null;
    }

    public function getClassifyStrAttribute()
    {
        return $this->getAttribute('classify') ? Data::find($this->getAttribute('classify'))->name : null;
    }

    public function getStatusStrAttribute()
    {
        return  self::STATUS[$this->getAttribute('status')];
    }
}

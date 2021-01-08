<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customerfund extends Model
{
    protected $fillable = [
        'id',
        'customer_id',
        'customer_name',
        'amount',
        'deduct_amount',
    ];

    protected $appends = ['style_str', 'type_str'];

    const STYLE = [
        0 => '结汇',
        1 => '付款',
        2 => '退税结算',
        3 => '退税预抵扣',
        4 => '退税抵扣',
        5 => '取消预抵扣',
    ];

    const TYPE = [
        0 => '增加',
        1 => '减少',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStyleStrAttribute()
    {
        return array_get(static::STYLE, $this->getAttribute('style'), '未知');
    }

    public function getTypeStrAttribute()
    {
        return array_get(static::TYPE, $this->getAttribute('type'), '未知');
    }
}
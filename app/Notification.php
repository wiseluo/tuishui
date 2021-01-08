<?php

namespace App;

class Notification extends BaseModel
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'status',
        'readed',
        'cid',
    ];
    protected $appends = [
        'status_str',
        'readed_str'
    ];
    const STATUS = [
        1=>'系统通知',
    ];
    const READED = [
        0=>'未读',
        1=>'已读'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function getStatusStrAttribute()
    {
        return array_get(static::STATUS, $this->getAttribute('status'), '系统通知');
    }

    public function getReadedStrAttribute()
    {
        return array_get(static::READED, $this->getAttribute('readed'), '未知');
    }
}

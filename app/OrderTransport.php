<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class OrderTransport extends Model
{
    use Notifiable;

    protected $table = 'order_transport';

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

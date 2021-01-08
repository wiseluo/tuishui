<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/25
 * Time: 13:14
 */

namespace App\Http\Traits;


use Illuminate\Database\Eloquent\Builder;

trait Status
{
    public static function countStatus()
    {
        $quantity = static::groupBy('status')
            ->selectRaw('COUNT(1) AS count, status')
            ->pluck('count', 'status')
            ->toArray();
        return [0 => array_sum($quantity)] + $quantity;
    }

    public static function renderStatus()
    {
        //$qtys = static::countStatus();
        $statuses = static::STATUS;
        return compact('statuses');
    }

    public function scopeStatus(Builder $query, $status)
    {
        return $query->when($status, function ($query) use ($status) {
            return $query->whereStatus($status);
        });
    }

    public function getStatusStrAttribute()
    {
        return array_get(static::STATUS, $this->getAttribute('status'), '未知');
    }
}
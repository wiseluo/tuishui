<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/14
 * Time: 14:33
 */

namespace App\Http\Traits;



use Illuminate\Database\Eloquent\Builder;

trait Search
{
    public function scopeSearchDate(Builder $query, $column, $start = null, $end = null, $dateTime = false)
    {
        if (false !== $dateTime) {
            return $this->scopeSearchDateTime(...func_get_args());
        }

        return $query->whereBetween($column, $this->trans($start, $end));
    }

    public function scopeSearchDateTime(Builder $query, $column, $start =null , $end = null)
    {
        list($start, $end) = $this->trans($start, $end);

        return $query->whereBetween($column, [$start . ' 00:00:01', $end . ' 23:59:59']);
    }

    protected function trans(...$args)
    {
        //TODO ::判断合法时间格式
        return [
            reset($args) ?: '1970-01-01',
            next($args) ?: date('Y-m-d'),
        ];
    }
}
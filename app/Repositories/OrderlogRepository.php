<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/29
 * Time: 14:34
 */

namespace App\Repositories;

use App\Orderlog;

class OrderlogRepository
{
    protected $orderlog;

    public function __construct(Orderlog $orderlog)
    {
        $this->orderlog = $orderlog;
    }

    public function save($data, ...$args)
    {
        $this->orderlog->fill($data);
        $this->orderlog->save();
        return $this->orderlog->id;
    }

    public function update($params, $id)
    {
        $orderlog = $this->orderlog->find($id);

        return (int) $orderlog->update($params);
    }

    public function delete($id)
    {
        return Orderlog::destroy($id);
    }

    public function select($id)
    {
        return Orderlog::findOrFail($id);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/29
 * Time: 14:34
 */

namespace App\Repositories;

use App\Customerlog;

class CustomerlogRepository
{
    protected $customerlog;

    public function __construct(Customerlog $customerlog)
    {
        $this->customerlog = $customerlog;
    }

    public function save($data, ...$args)
    {
        $this->customerlog->fill($data);
        $this->customerlog->save();
        return $this->customerlog->id;
    }

    public function update($params, $id)
    {
        $product = $this->customerlog->find($id);

        return (int) $product->update($params);
    }

    public function delete($id)
    {
        return Customerlog::destroy($id);
    }

    public function select($id)
    {
        return Customerlog::findOrFail($id);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/29
 * Time: 14:34
 */

namespace App\Repositories;

use App\Productlog;

class ProductlogRepository
{
    protected $productlog;

    public function __construct(Productlog $productlog)
    {
        $this->productlog = $productlog;
    }

    public function save($data, ...$args)
    {
        $this->productlog->fill($data);
        $this->productlog->save();
        return $this->productlog->id;
    }

    public function update($params, $id)
    {
        $product = $this->productlog->find($id);

        return (int) $product->update($params);
    }

    public function delete($id)
    {
        return Productlog::destroy($id);
    }

    public function select($id)
    {
        return Productlog::findOrFail($id);
    }
}
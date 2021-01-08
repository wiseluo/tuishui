<?php

namespace App\Repositories;

use App\DrawerProduct;

class DrawerProductRepository
{
    public function save($data)
    {
        $drawer_product = new DrawerProduct($data);
        $drawer_product->save();
        return $drawer_product->id;
    }

    public function update($params, $id)
    {
        $drawer_product = DrawerProduct::find($id);
        return (int) $drawer_product->update($params);
    }

    public function destory($id)
    {
        return DrawerProduct::destroy($id);
    }

    public function delete($where)
    {
        return DrawerProduct::where($where)->delete();
    }

    public function findWhere($where)
    {
        return DrawerProduct::where($where)->first();
    }
}
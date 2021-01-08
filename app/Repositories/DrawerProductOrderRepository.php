<?php

namespace App\Repositories;

use App\DrawerProductOrder;

class DrawerProductOrderRepository
{
    public function save($data)
    {
        $drawer_product_order = new DrawerProductOrder($data);
        $drawer_product_order->save();
        return $drawer_product_order->id;
    }

    public function update($params, $id)
    {
        $drawer_product_order = DrawerProductOrder::find($id);
        return (int) $drawer_product_order->update($params);
    }

    public function destory($id)
    {
        return DrawerProductOrder::destroy($id);
    }

    public function delete($where)
    {
        return DrawerProductOrder::where($where)->delete();
    }

    public function find($id)
    {
        return DrawerProductOrder::find($id);
    }

    public function findWhere($where)
    {
        return DrawerProductOrder::where($where)->first();
    }
}
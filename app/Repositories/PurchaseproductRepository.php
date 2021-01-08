<?php

namespace App\Repositories;

use App\Purchaseproduct;

class PurchaseproductRepository
{
    protected $purchaseproduct;

    public function __construct(Purchaseproduct $purchaseproduct)
    {
        $this->purchaseproduct = $purchaseproduct;
    }

    public function save($data, ...$args)
    {
        // $this->purchaseproduct->fill($data); //循环save不能用同一模型
        // $res = $this->purchaseproduct->save();
        // return $res;
        $purchaseproducts = new Purchaseproduct($data);
        $purchaseproducts->save();
        return $purchaseproducts->id;
    }

    public function update($params, $id)
    {
        $purchaseproducts = $this->purchaseproduct->find($id);
        return (int) $purchaseproducts->update($params);
    }

    public function forceDeleteByPurchaseId($purchase_id)
    {
        return $this->purchaseproduct->where(['purchase_id'=> $purchase_id])->forceDelete();
    }

    public function findOrFail($id)
    {
        return $this->purchaseproduct->findOrFail($id);
    }

    public function productListByPurchaseId($purchase_id)
    {
        return $this->purchaseproduct->where(['purchase_id'=> $purchase_id])->get();
    }
}
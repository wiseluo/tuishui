<?php

namespace App\Repositories;

use App\Purchase;

class PurchaseRepository
{
    protected $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function save($data, ...$args)
    {
        $this->purchase->fill($data);
        $res = $this->purchase->save();
        return $res;
    }

    public function update($params, $id)
    {
        $purchases = $this->purchase->find($id);
        return (int) $purchases->update($params);
    }

    public function forceDeleteById($id)
    {
        return $this->purchase->where(['id'=> $id])->forceDelete();
    }

    public function findOrFail($id)
    {
        return $this->purchase->findOrFail($id);
    }

}
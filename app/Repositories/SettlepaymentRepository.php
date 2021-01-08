<?php

namespace App\Repositories;

use App\Settlepayment;

class SettlepaymentRepository
{

    public function save($data, ...$args)
    {
        $settlepayment = new Settlepayment($data);
        $settlepayment->save();
        return $settlepayment->id;
    }

    public function update($params, $id)
    {
        $settlepayment = Settlepayment::find($id);
        return (int) $settlepayment->update($params);
    }

    public function forceDeleteBySettlementId($settlement_id)
    {
        return Settlepayment::where(['settlement_id'=> $settlement_id])->forceDelete();
    }

    public function find($id)
    {
        return Settlepayment::find($id);
    }

    public function select($where)
    {
        return Settlepayment::where($where)->get();
    }
}
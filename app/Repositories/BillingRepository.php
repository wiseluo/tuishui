<?php

namespace App\Repositories;

use App\Billing;
use App\Data;

class BillingRepository
{
    public function save($params, ...$args)
    {
        $billing = new Billing($params);
        return (int) $billing->save();
    }

    public function update($params, $id)
    {
        $billing = Billing::where('data_id', $id)->get();
        if(count($billing)){
            return (int) $billing[0]->update($params);
        }else{
            $billing = new Billing($params);
            return (int) $billing->save();
        }
    }

    public function delete($id)
    {

        return Billing::where('data_id', $id)->forceDelete();
    }

    public function select($id)
    {
        return Data::with('billing')->findOrFail($id);
    }

}
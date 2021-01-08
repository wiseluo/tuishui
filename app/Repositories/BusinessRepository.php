<?php

namespace App\Repositories;

use App\Business;

class BusinessRepository
{
    public function save($data, ...$args)
    {
        $business = new Business($data);

        return (int) $business->save();
    }

    public function update($params, $id)
    {
        $business = Business::find($id);
        return (int) $business->update($params);
    }

    public function delete($id)
    {
        return Business::destroy($id);
    }

    public function select($id)
    {
        return Business::findOrFail($id);
    }

}
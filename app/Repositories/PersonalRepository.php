<?php

namespace App\Repositories;

use App\Personal;

class PersonalRepository
{
    public function save($data, ...$args)
    {
        $personal = new Personal($data);
        $personal->save();
        return $personal->id;
    }

    public function update($params, $id)
    {
        $personal = Personal::findOrFail($id);

        return (int) $personal->update($params);
    }

    public function delete($id)
    {
        return Personal::destroy($id);
    }

    public function find($id)
    {
        return Personal::find($id);
    }

    public function getPersonalByUserId($id)
    {
        return Personal::where('created_user_id', $id)->first();
    }

    public function getOnePersonalByWhere($where)
    {
        return Personal::where($where)->first();
    }
    
    public function countByWhere($where)
    {
        return Personal::where($where)->count();
    }
}
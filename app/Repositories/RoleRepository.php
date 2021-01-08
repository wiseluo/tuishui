<?php

namespace App\Repositories;

use App\Role;

class RoleRepository
{
    public function save($params, ...$args)
    {
        $role = new Role($params);
        $role->save();
        $role->attachPermissions(array_unique(explode(',', array_get($params, 'permission_id'))));
        return 1;
    }

    public function update($params, $id)
    {
        $role = Role::find($id);
        $role->detachPermissions();
        $role->attachPermissions(array_unique(explode(',', array_get($params, 'permission_id'))));
        return (int) $role->update($params);
    }

    public function delete($id)
    {
        return Role::destroy($id);
    }

    public function get($id)
    {
        return Role::findOrFail($id);
    }

    public function select($id)
    {
        return Role::findOrFail($id);
    }

}
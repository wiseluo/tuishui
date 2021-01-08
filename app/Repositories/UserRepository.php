<?php

namespace App\Repositories;

use App\Role;
use App\User;

class UserRepository
{
    public function save($params, ...$args)
    {
        $user = new User($params);
        return (int) $user->save();
    }

    public function update($params, $id)
    {
        $user = User::find($id);
        $user->detachRoles();
        $user->attachRoles(array_unique(explode(',', array_get($params, 'role_id'))));
        return (int) $user->update($params);
    }

    public function delete($id)
    {
        return User::destroy($id);
    }

    public function select($id)
    {
        return User::findOrFail($id);
    }

    public function getUsersByRole($name)
    {
        $role = Role::whereName($name)->first();
        if ($role) {
            return $role->users;
        }
        return null;
    }
    
    public function findByWhere($where)
    {
        return User::where($where)->first();
    }

    public function getUserMenuList($id)
    {
        return User::with(['roles.perms'=> function($query) {
                $query->where('ismenu', '=', 1);
            }])
            ->find($id);
    }
}
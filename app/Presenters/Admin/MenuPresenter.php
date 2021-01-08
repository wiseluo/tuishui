<?php

namespace App\Presenters\Admin;

use App\User;
use App\Repositories\UserRepository;

class MenuPresenter
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
	/*
	 * 左侧菜单权限显示
	 */
	public function menuList()
	{
		$user_id = request()->user('admin')->id;
		$user = $this->userRepository->getUserMenuList($user_id);
		//dd($user);
		if($user){
			$roles = $user->roles;
			if($roles){
				$permArr = [];
				foreach($roles as $role){
					$perms = $role->perms;
					if($perms){
						foreach($perms as $permission){
							if($permission->ismenu == 1){
								$permArr[] = $permission->name;
							}
						}
					}
				}
				$permArr = array_unique($permArr);
			}
		}
		return $permArr;
	}
	
}
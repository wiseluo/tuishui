<?php

namespace App\Presenters\Member;

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
		$user_id = request()->user('member')->id;
		$user = $this->userRepository->getUserMenuList($user_id);
		//$user = User::find($user_id);
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
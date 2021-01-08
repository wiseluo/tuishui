<?php

namespace App\Http\Controllers\Member;

use App\Rbac\Permission;
use App\Rbac\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index(){

        $owner = new Role();
        $owner->name         = 'owner';
        $owner->display_name = 'Project Owner'; // optional
        $owner->description  = 'User is the owner of a given project'; // optional
        $owner->save();

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'User Administrator'; // optional
        $admin->description  = 'User is allowed to manage and edit other users'; // optional
        $admin->save();

        $test = new Permission();
        $test->name('test');
        $test->save();
        dd(Auth::user()->can('test')) ;
    }
}

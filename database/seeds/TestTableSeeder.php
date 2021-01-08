<?php

use Illuminate\Database\Seeder;

class TestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tests')->insert([
          ['name'=> 'system/permission', 'display_name'=> '权限管理', 'description'=> '权限管理', 'ismenu'=> 1],
          ['name'=> 'system/role', 'display_name'=> '角色管理', 'description'=> '角色管理', 'ismenu'=> 1],
          ['name'=> 'system/account', 'display_name'=> '账号管理', 'description'=> '账号管理', 'ismenu'=> 1],
          ['name'=> 'data_manage', 'display_name'=> '数据维护', 'description'=> '数据维护', 'ismenu'=> 1],
          ['name'=> 'business', 'display_name'=> '业务管理', 'description'=> '业务管理', 'ismenu'=> 1],
          ['name'=> '/', 'display_name'=> '首页访问', 'description'=> '首页访问', 'ismenu'=> 0],
          ['name'=> 'report', 'display_name'=> '报表管理', 'description'=> '报表管理', 'ismenu'=> 1]
        ]);
    }
}

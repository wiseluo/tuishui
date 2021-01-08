<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
        ['name'=> '系统默认', 'username'=> '系统默认', 'email'=> '1', 'password'=> '123456'],
        ['name'=> '网络科技开发账号', 'username'=> 'cs001', 'email'=> '001@163.com', 'password'=> '123456']
      ]);
    }
}

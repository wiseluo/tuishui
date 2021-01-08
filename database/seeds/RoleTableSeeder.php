<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('roles')->insert([
        ['name'=> 'owner', 'display_name'=> 'Project Owner', 'description'=> 'User is the owner of a given project'],
        ['name'=> 'salesman', 'display_name'=> '业务员', 'description'=> '业务员']
      ]);
    }
}

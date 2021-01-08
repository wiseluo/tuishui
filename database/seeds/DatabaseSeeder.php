<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // $this->call(TestTableSeeder::class);
      $this->call(PermissionTableSeeder::class);
      $this->call(UserTableSeeder::class);
      $this->call(RoleTableSeeder::class);
      $this->call(PermissionRoleTableSeeder::class);
      $this->call(RoleUserTableSeeder::class);
    }
}

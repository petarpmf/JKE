<?php
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert(['id' => '1', 'name' => 'Admin']);
        DB::table('roles')->insert(['id' => '2', 'name' => 'Client']);
        DB::table('roles')->insert(['id' => '3', 'name' => 'Inspector']);
        DB::table('roles')->insert(['id' => '4', 'name' => 'Guest']);
    }
}

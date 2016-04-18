<?php
use Illuminate\Database\Seeder;

class UserExperienceTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('experiences')->insert(['id' => 1, 'experience_name' => 'Inspector']);
        DB::table('experiences')->insert(['id' => 2, 'experience_name' => 'Contractor']);
        DB::table('experiences')->insert(['id' => 3, 'experience_name' => 'Pipeline Owner']);
        DB::table('experiences')->insert(['id' => 4, 'experience_name' => 'Other Position']);
    }
}

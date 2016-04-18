<?php
use Illuminate\Database\Seeder;

class DesiredJobTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('desired_jobs')->insert(['id' => '1', 'name' => 'Administration']);
        DB::table('desired_jobs')->insert(['id' => '2', 'name' => 'Chief Inspector']);
        DB::table('desired_jobs')->insert(['id' => '3', 'name' => 'Coating Inspector']);
        DB::table('desired_jobs')->insert(['id' => '4', 'name' => 'Environmental Inspector']);
        DB::table('desired_jobs')->insert(['id' => '5', 'name' => 'HDD Inspector']);
        DB::table('desired_jobs')->insert(['id' => '6', 'name' => 'Safety Inspector']);
        DB::table('desired_jobs')->insert(['id' => '7', 'name' => 'Utility Inspector']);
        DB::table('desired_jobs')->insert(['id' => '8', 'name' => 'Welding Inspector']);
        DB::table('desired_jobs')->insert(['id' => '9', 'name' => 'Facility Chief Inspector']);
        DB::table('desired_jobs')->insert(['id' => '10', 'name' => 'Manager of Construction & Quality Management']);
        DB::table('desired_jobs')->insert(['id' => '11', 'name' => 'Materials Manager/Materials Coordinator']);
        DB::table('desired_jobs')->insert(['id' => '12', 'name' => 'Electrical Inspector']);
        DB::table('desired_jobs')->insert(['id' => '13', 'name' => 'Road Inspector/Supervisor']);

        DB::table('desired_jobs')->insert(['id' => '99', 'name' => 'Other']);
    }
}

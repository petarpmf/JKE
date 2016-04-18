<?php
use Illuminate\Database\Seeder;

class UserQualificationTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('qualifications')->insert(['id' => '1', 'qualification_name' => 'Understands and Delivers on Clients Goals']);
        DB::table('qualifications')->insert(['id' => '2', 'qualification_name' => 'Clearly Defines Expectation of Contractor']);
        DB::table('qualifications')->insert(['id' => '3', 'qualification_name' => 'Influences Contractor to Achieve Project Goals']);
        DB::table('qualifications')->insert(['id' => '4', 'qualification_name' => 'Identifies Risk, and Proactively Communicates to All']);
        DB::table('qualifications')->insert(['id' => '5', 'qualification_name' => 'Consistently Documents Critical Project Information']);
        DB::table('qualifications')->insert(['id' => '6', 'qualification_name' => 'Effectively Communicates with Project Team, uses “We” vs “I”']);
        DB::table('qualifications')->insert(['id' => '7', 'qualification_name' => 'Understands Land Owner and Public Official Concerns and Effectively Communicates']);
        DB::table('qualifications')->insert(['id' => '8', 'qualification_name' => 'Dedicated and Committed to Projects']);
        DB::table('qualifications')->insert(['id' => '9', 'qualification_name' => 'Develops Self']);
        DB::table('qualifications')->insert(['id' => '10', 'qualification_name' => 'Develops Others']);
    }
}

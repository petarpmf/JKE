<?php
use Illuminate\Database\Seeder;

class UserCertificateTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('certificates')->insert(['id' => '1', 'certificate_type' => 'Operator Certifications']);
        DB::table('certificates')->insert(['id' => '2', 'certificate_type' => 'Safety Certifications']);
        DB::table('certificates')->insert(['id' => '3', 'certificate_type' => 'Industry Certifications']);

        DB::table('certificates')->insert(['id' => '99', 'certificate_type' => 'Other Certifications']);
    }
}

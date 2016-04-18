<?php

use Illuminate\Database\Eloquent\Model;
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
        Model::unguard();

        DB::table('users')->delete();
        DB::table('desired_jobs')->delete();
        DB::table('experiences')->delete();
        DB::table('qualifications')->delete();
        DB::table('certificates')->delete();

        DB::table('tokens')->delete();
        DB::table('roles')->delete();

        DB::table('users_desired_jobs')->delete();
        DB::table('users_experiences')->delete();
        DB::table('users_qualifications')->delete();
        DB::table('companies')->delete();
        DB::table('projects')->delete();
        DB::table('users_companies')->delete();

        $this->call('UserTableSeeder');
        //$this->call('LessonTableSeeder');
        $this->call('RoleTableSeeder');
        $this->call('DesiredJobTableSeeder');
        $this->call('UserExperienceTableSeeder');
        $this->call('UserQualificationTableSeeder');
        $this->call('UserCertificateTableSeeder');
        $this->call('ReferenceTableSeeder');
        $this->call('CompanyTableSeeder');
        $this->call('ProjectTableSeeder');
        $this->call('UserCompanyTableSeeder');
    }

}

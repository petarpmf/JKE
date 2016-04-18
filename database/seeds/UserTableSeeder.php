<?php
use App\Http\Models\ProfileAdmin;
use App\Http\Models\ProfileClient;
use App\Http\Models\ProfileInspector;
use App\Http\Models\User;
use Illuminate\Database\Seeder;
use Rhumsaa\Uuid\Uuid;

/**
 * User: igor.talevski@it-labs.com
 * Date: 7/1/2015
 * Time: 1:44 PM
 */
class UserTableSeeder extends Seeder
{
    public function run()
    {
        //Desired job positions
        $dj = ['Administration',
            'Chief Inspector',
            'Coating Inspector',
            'Environmental Inspector',
            'HDD Inspector',
            'Safety Inspector',
            'Utility Inspector',
            'Welding Inspector'];

        //Create profile details in profile_admins table for admin.
        $faker = Faker\Factory::create();
        //create default jke@yopmail.com
        $profile = ProfileAdmin::create([
            'id'=>1,
            'image_id' => null,
            'file_id' => null,
            'street_address' => $faker->address,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postcode,
            'country' => $faker->country,
            'mobile_phone' => $faker->phoneNumber,
            'other_phone' => $faker->phoneNumber,
            'resume_link' => $faker->url,
            'job_title' => $faker->randomElement($dj),
            'summary' => $faker->text,
            'jke_note' => $faker->text,
            'currently_seeking_opportunities' => $faker->randomElement($array = array('1', '0')),
            'other_jobs'=>$faker->text
        ]);
        //Create user details for admin.
        factory('App\Http\Models\User')->create(
            [
                'email' => 'jke@yopmail.com',
                'profile_type'=>'App\Http\Models\ProfileAdmin',
                'profile_id'=>$profile->id,
                'first_name' => 'Admin',
                'last_name' => 'Master',
                'password' => 'P@ssw0rd',
                'role_id' => 1
            ]);

        //create default Dan@joeknowsenergy.com
        $profile = ProfileAdmin::create([
            'id'=>2,
            'image_id' => null,
            'file_id' => null,
            'street_address' => $faker->address,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postcode,
            'country' => $faker->country,
            'mobile_phone' => $faker->phoneNumber,
            'other_phone' => $faker->phoneNumber,
            'resume_link' => $faker->url,
            'job_title' => $faker->randomElement($dj),
            'summary' => $faker->text,
            'jke_note' => $faker->text,
            'currently_seeking_opportunities' => $faker->randomElement($array = array('1', '0')),
            'other_jobs'=>$faker->text
        ]);
        //Create user details for Dan@joeknowsenergy.com.
        factory('App\Http\Models\User')->create(
            [
                'email' => 'Dan@joeknowsenergy.com',
                'profile_type'=>'App\Http\Models\ProfileAdmin',
                'profile_id'=>$profile->id,
                'first_name' => 'Dan',
                'last_name' => 'Lorenz',
                'password' => 'DanP@ssw0rd',
                'role_id' => 1
            ]);

        //create default Steve@joeknowsenergy.com
        $profile = ProfileAdmin::create([
            'id'=>2,
            'image_id' => null,
            'file_id' => null,
            'street_address' => $faker->address,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postcode,
            'country' => $faker->country,
            'mobile_phone' => $faker->phoneNumber,
            'other_phone' => $faker->phoneNumber,
            'resume_link' => $faker->url,
            'job_title' => $faker->randomElement($dj),
            'summary' => $faker->text,
            'jke_note' => $faker->text,
            'currently_seeking_opportunities' => $faker->randomElement($array = array('1', '0')),
            'other_jobs'=>$faker->text
        ]);
        //Create user details for Steve@joeknowsenergy.com.
        factory('App\Http\Models\User')->create(
            [
                'email' => 'Steve@joeknowsenergy.com',
                'profile_type'=>'App\Http\Models\ProfileAdmin',
                'profile_id'=>$profile->id,
                'first_name' => 'Steve',
                'last_name' => 'Hazelbaker',
                'password' => 'SteveP@ssw0rd',
                'role_id' => 1
            ]);

        //create default jill@joeknowsenergy.com
        $profile = ProfileAdmin::create([
            'id'=>2,
            'image_id' => null,
            'file_id' => null,
            'street_address' => $faker->address,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postcode,
            'country' => $faker->country,
            'mobile_phone' => $faker->phoneNumber,
            'other_phone' => $faker->phoneNumber,
            'resume_link' => $faker->url,
            'job_title' => $faker->randomElement($dj),
            'summary' => $faker->text,
            'jke_note' => $faker->text,
            'currently_seeking_opportunities' => $faker->randomElement($array = array('1', '0')),
            'other_jobs'=>$faker->text
        ]);
        //Create user details for jill@joeknowsenergy.com.
        factory('App\Http\Models\User')->create(
            [
                'email' => 'jill@joeknowsenergy.com',
                'profile_type'=>'App\Http\Models\ProfileAdmin',
                'profile_id'=>$profile->id,
                'first_name' => 'Jill',
                'last_name' => 'Kelly',
                'password' => 'JillP@ssw0rd',
                'role_id' => 1
            ]);

        //create default banne@it-labs.com
        $profile = ProfileAdmin::create([
            'id'=>2,
            'image_id' => null,
            'file_id' => null,
            'street_address' => $faker->address,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postcode,
            'country' => $faker->country,
            'mobile_phone' => $faker->phoneNumber,
            'other_phone' => $faker->phoneNumber,
            'resume_link' => $faker->url,
            'job_title' => $faker->randomElement($dj),
            'summary' => $faker->text,
            'jke_note' => $faker->text,
            'currently_seeking_opportunities' => $faker->randomElement($array = array('1', '0')),
            'other_jobs'=>$faker->text
        ]);
        //Create user details for banne@it-labs.com.
        factory('App\Http\Models\User')->create(
            [
                'email' => 'banne@it-labs.com',
                'profile_type'=>'App\Http\Models\ProfileAdmin',
                'profile_id'=>$profile->id,
                'first_name' => 'Branislav',
                'last_name' => 'Gjorcevski',
                'password' => 'BanneP@ssw0rd',
                'role_id' => 1
            ]);
        for ($i = 2; $i < 51; $i++) {
            $user = factory('App\Http\Models\User')->create();
            if($user->role_id==1){ // if user is Admin
                $faker = Faker\Factory::create();
                $profile = ProfileAdmin::create([
                    'id'=>Uuid::uuid4()->toString(),
                    'image_id' => null,
                    'file_id' => null,
                    'street_address' => $faker->address,
                    'city' => $faker->city,
                    'state' => $faker->state,
                    'zip' => $faker->postcode,
                    'country' => $faker->country,
                    'mobile_phone' => $faker->phoneNumber,
                    'other_phone' => $faker->phoneNumber,
                    'resume_link' => $faker->url,
                    'job_title' => $faker->randomElement($dj),
                    'summary' => $faker->text,
                    'jke_note' => $faker->text,
                    'currently_seeking_opportunities' => $faker->randomElement($array = array('1', '0')),
                    'other_jobs'=>$faker->text
                    ]);
                User::where('id','=',$user->id)->update(['profile_type'=>'App\Http\Models\ProfileAdmin', 'profile_id'=>$profile->id]);
            }else if($user->role_id==2){  //if user is Client
                $faker = Faker\Factory::create();
                $profile = ProfileClient::create([
                    'id'=>Uuid::uuid4()->toString(),
                    'image_id' => null,
                    'jke_note' => $faker->text
                    //'file_id' => null,
                    //'street_address' => $faker->address,
                    //'city' => $faker->city,
                    //'state' => $faker->state,
                    //'zip' => $faker->postcode,
                    //'country' => $faker->country,
                    //'mobile_phone' => $faker->phoneNumber,
                    //'other_phone' => $faker->phoneNumber,
                    //'resume_link' => $faker->url,
                    //'job_title' => $faker->randomElement($dj),
                    //'summary' => $faker->text,

                    //'currently_seeking_opportunities' => $faker->randomElement($array = array('1', '0')),
                    //'other_jobs'=>$faker->text
                ]);
                User::where('id','=',$user->id)->update(['profile_type'=>'App\Http\Models\ProfileClient', 'profile_id'=>$profile->id]);
            }else if($user->role_id==3){ //if user is Inspector
                $faker = Faker\Factory::create();
                $profile = ProfileInspector::create([
                    'id'=>Uuid::uuid4()->toString(),
                    'image_id' => null,
                    'file_id' => null,
                    'street_address' => $faker->address,
                    'city' => $faker->city,
                    'state' => $faker->state,
                    'zip' => $faker->postcode,
                    'country' => $faker->country,
                    'mobile_phone' => $faker->phoneNumber,
                    'other_phone' => $faker->phoneNumber,
                    'resume_link' => $faker->url,
                    'job_title' => $faker->randomElement($dj),
                    'summary' => $faker->text,
                    'jke_note' => $faker->text,
                    'currently_seeking_opportunities' => $faker->randomElement($array = array('1', '0')),
                    'other_jobs'=>$faker->text
                ]);
                User::where('id','=',$user->id)->update(['profile_type'=>'App\Http\Models\ProfileInspector', 'profile_id'=>$profile->id]);
            }
        }
    }

}

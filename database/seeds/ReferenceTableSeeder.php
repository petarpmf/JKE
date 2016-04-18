<?php
use App\Http\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Jke\Jobs\Models\Reference;

class ReferenceTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('references')->delete();
        $faker = Faker\Factory::create();
        $user = User::where('email','=','jke@yopmail.com')->first();
        for ($i = 0; $i < 30; $i++) {
            $references = Reference::create([
                'user_id' => $user->id,
                'reference_name' => $faker->firstName,
                'reference_phone' => $faker->phoneNumber,
                'reference_email' => $faker->email,
                'reference_company' => $faker->company,
                'reference_title' => $faker->title,
                'created_at' => $faker->randomElement(['2015-10-07 10:46:37', '2015-11-09 10:46:37', '2016-11-09 10:46:37']),
                'updated_at' => $faker->randomElement(['2015-05-07 10:50:37', '2015-02-09 12:46:37', '2016-11-09 10:46:37'])
            ]);
        }
    }
}
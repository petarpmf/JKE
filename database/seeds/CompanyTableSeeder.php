<?php
use App\Http\Models\Project;
use Illuminate\Database\Seeder;
use Rhumsaa\Uuid\Uuid;

/**
 * User: igor.talevski@it-labs.com
 * Date: 7/1/2015
 * Time: 1:44 PM
 */
class CompanyTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 300; $i++) {

            try {
                $company = factory('App\Http\Models\Company')->create();
                $faker = Faker\Factory::create();

                Project::create([
                    'id' => (string)Uuid::uuid4(),
                    'project_name' => $faker->name,
                    'date_report_completed' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    'owner' => $faker->name,
                    'company_id' => $company->id,
                    'project_status' => $faker->randomElement([0, 1]),
                    'phase_name' => $faker->name,
                    'service_level' => $faker->randomElement([0, 1, 2, 3, 4, 5]),
                    'street_address' => $faker->address,
                    'city' => $faker->city,
                    'zip' => $faker->postcode,
                    'state' => $faker->state,
                    'country' => $faker->country,
                    'start_date' => $faker->randomElement(['2015-05-06', '2015-06-09', '2016-07-09']),
                    'end_date' => $faker->randomElement(['2015-10-07', '2015-11-09', '2016-11-09']),
                    'critical_skills' => $faker->randomElement([0, 1]),
                    'uniform' => $faker->randomElement([0, 1]),
                    'audit' => $faker->randomElement([0, 1]),
                    'mentor' => $faker->randomElement([0, 1]),
                    'sop_training_test' => $faker->randomElement([0, 1]),
                    'oq_required' => $faker->randomElement([0, 1]),
                    'drug_test' => $faker->randomElement(["None", "DOT", "Standard"]),
                    'safety_training_test' => $faker->randomElement([0, 1]),
                    'envir_training_test' => $faker->randomElement([0, 1]),
                    'software_forms' => $faker->randomElement([0, 1]),
                    'how_ot_handled_admin' => $faker->text,
                    'per_diem_admin' => $faker->randomElement([0, 1, 2, 3, 4, 5]),
                    'electronics' => $faker->randomElement([0, 1, 2, 3, 4, 5]),
                    'truck' => $faker->randomElement([0, 1, 2, 3, 4, 5]),
                    'mileage_admin' => $faker->text,
                    'day_rate' => $faker->randomElement([0, 1]),
                    'mileage' => $faker->randomElement([0, 1]),
                    'per_diem' => $faker->randomElement([0, 1]),
                    'sales_tax_required' => $faker->randomElement([0, 1])//,

                    //'created_at' => $faker->randomElement(['2015-10-07 10:46:37', '2015-11-09 10:46:37', '2016-11-09 10:46:37']),
                    //'updated_at' => $faker->randomElement(['2015-05-07 10:50:37', '2015-02-09 12:46:37', '2016-11-09 10:46:37'])
                ]);
            } catch (\Exception $ex) {
                // igonre
            }

        }
    }
}

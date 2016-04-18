<?php
use Rhumsaa\Uuid\Uuid;

/*
$factory->define('App\Http\Models\User', function ($faker) {
    return [
        'id' => (string)Uuid::uuid4(),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => 'testpass',
        'role_id' => (string) rand(1, 3),
        'remember_token' => str_random(18),
        'image_id' => null,
        'forgot_token' => null,
        'deleted_at' => null,
    ];
});

$factory->define('Itlabs\Lessons\Models\Lesson', function ($faker) {
    return [
        'id' => (string)Uuid::uuid4(),
        'name' => $faker->sentence(5),
        'image_id' => (string)Uuid::uuid4(),
        'content' => '{"tag":"div","attr":{"class":["lms-editor-content"]},"child":[{"tag":"p","text":"'.$faker->sentence(40).'"}]}',
        'order' => (int) rand(1, 10),
    ];
});

$factory->define('Itlabs\Lessons\Models\Chapter', function ($faker) {
    return [
        'id' => (string)Uuid::uuid4(),
        'name' => $faker->sentence(5),
        'image_id' => (string)Uuid::uuid4(),
        'content' => '{"tag":"div","attr":{"class":["lms-editor-content"]},"child":[{"tag":"p","text":"'.$faker->sentence(40).'"}]}',
        'order' => (int) rand(1, 10),
        'lesson_id' => Uuid::uuid4()->__toString(),
    ];
});

$factory->define('Itlabs\Media\Models\Media', function ($faker) {
    return [
        'id' => (string)Uuid::uuid4(),
        'collection'=>$faker->randomElement($array = array ('all','user','test', 'group')),
        'original_name' => $faker->randomElement($array = array ('image.jpg','image1.jpg','image2.jpg', 'image3.jpg')),
        'generated_name' => $faker->randomElement($array = array ('Adff543.jpg','TrFFh5.jpg','34FhtgE.jpg', 'MdRTDd.jpg')),
        'type' => $faker->randomElement($array = array ('image/jpeg','image/jpg','image/gif', 'text/text')),
        'cloudfront_url' => 'lmscontent.it-labs.com'
    ];
});
*/
$factory->define('App\Http\Models\User', function ($faker) {

    $profileType = ['App\Http\Models\ProfileAdmin',
        'App\Http\Models\ProfileClient',
        'App\Http\Models\ProfileInspector'
        ];

    return [
        'id' => (string)Uuid::uuid4(),
        'role_id' => (string)rand(2, 3),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => 'P@ssw0rd',
        'remember_token' => str_random(18),
        'forgot_token' => null,
        'deleted_at' => null,
    ];
});

$factory->define('Jke\Jobs\Models\Reference', function ($faker) {

    return [
        'id' => (string)Uuid::uuid4(),
        'user_id' => (string)Uuid::uuid4(),
        'reference_name' => $faker->firstName,
        'reference_phone' => $faker->phoneNumber,
        'reference_email' => $faker->email,
        'reference_company' => $faker->company,
        'reference_title' => $faker->title,
        'created_at' => $faker->randomElement(['2015-10-07 10:46:37', '2015-11-09 10:46:37', '2016-11-09 10:46:37']),
        'updated_at' => $faker->randomElement(['2015-05-07 10:50:37', '2015-02-09 12:46:37', '2016-11-09 10:46:37'])
    ];
});

$factory->define('App\Http\Models\Company', function ($faker) {

    return [
        'id' => (string)Uuid::uuid4(),
        'company_name' => $faker->company . $faker->randomDigit,
        'company_email' => $faker->email,
        'phone_number' => $faker->phoneNumber,
        'street_address' => $faker->address,
        'city' => $faker->city,
        'zip' => $faker->postcode,
        'state' => $faker->state,
        'country' => $faker->country,
        'web_site' => $faker->url,
        'notes' => $faker->text,
        'created_at' => $faker->randomElement(['2015-10-07 10:46:37', '2015-11-09 10:46:37', '2016-11-09 10:46:37']),
        'updated_at' => $faker->randomElement(['2015-05-07 10:50:37', '2015-02-09 12:46:37', '2016-11-09 10:46:37'])
    ];
});

$factory->define('App\Http\Models\Project', function ($faker) {

    return [
        'id' => (string)Uuid::uuid4(),
        'project_name' => $faker->name,
        'date_report_completed' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'owner' => $faker->name,
        //'company_id'=>
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
        'field_tablet' => $faker->randomElement([0, 1]),
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
    ];
});

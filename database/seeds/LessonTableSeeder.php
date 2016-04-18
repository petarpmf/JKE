<?php
use Illuminate\Database\Seeder;

class LessonTableSeeder extends Seeder{
    public function run(){


        $faker = Faker\Factory::create();
        for ($i=0; $i < 31; $i++) {
            $testLesson = factory('Itlabs\Lessons\Models\Lesson')->create();
            for($j=0; $j < 31; $j++) {
                $testChapterData = factory('Itlabs\Lessons\Models\Chapter')->make()->toArray();
                $testChapterData['lesson_id'] = $testLesson->id;
                $testChapterData['name'] = $faker->sentence(5);
                $testChapter = factory('Itlabs\Lessons\Models\Chapter')->create($testChapterData);
            }


        }
    }

}
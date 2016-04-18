<?php
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 300; $i++) {

            try {
                factory('App\Http\Models\Project')->create();
            } catch (\Exception $ex) {
                // ignore
            }
        }
    }
}

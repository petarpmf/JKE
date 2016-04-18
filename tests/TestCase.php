<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestCase extends Laravel\Lumen\Testing\TestCase {


    public function setUp()
    {
        parent::setUp();
        $this->refreshApplication();
        $this->artisan('migrate');
    }

    protected $times = 1;

    //use DatabaseTransactions;
    //use DatabaseMigrations;


    //protected $connectionString = 'security';

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function seeInDatabase($table, array $data)
    {
        $count = $this->app->make('db')->table($table)->where($data)->count();

        $this->assertGreaterThan(0, $count, sprintf(
            'Unable to find row in database table [%s] that matched attributes [%s].', $table, json_encode($data)
        ));

        return $this;
    }

    public function checkRemovedFromDatabase($table, array $data)
    {
        $count = $this->app->make('db')->table($table)->where($data)->count();

        $this->assertEquals(0, $count, sprintf(
            'Row not deleted in database table [%s] that matched attributes [%s].', $table, json_encode($data)
        ));

        return $this;
    }

    public function createDummyUserInDatabase($user = []){


        return factory('App\Http\Models\User')->create();



    }

    public function times($times){
        $this->times--;
        return $this;

    }



}

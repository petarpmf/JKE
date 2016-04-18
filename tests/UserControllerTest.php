<?php
use Laravel\Lumen\Testing\ApplicationTrait;
use Laravel\Lumen\Testing\AssertionsTrait;
use Laravel\Lumen\Testing\CrawlerTrait;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest  //\\ extends TestCase
{



    public function __construct()
    {
        parent::__construct();

        //dd($this->app);
        //$this->instance('middleware.disable', true);

    }

    /**
     * @test
     */
    public function it_fetch_users()
    {
        //$this->withoutMiddleware(true);
        $this->app->instance('routemiddleware.disable', true);
        //dd($this->app);


        //arrange
        $dummyUser = $this->createDummyUserInDatabase();
        //dd($dummyUser);


        //act

        $reposnse = $this->getJson('/users');

//dd($reposnse);


        //$this->seeJsonContains(['data']);
        $this->assertResponseOk();




    }

    private function getJson($url,$method = 'GET')
    {

        return json_decode($this->call($method,$url)->getContent());

    }

}
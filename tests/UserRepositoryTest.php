<?php

class UserRepositoryTest extends TestCase {

    /*
     * @test
     */
    public function test_creating_user()
    {
        $userRepository = $this->app->make('App\Http\Interfaces\UserInterface');
        $testUser = factory('App\Http\Models\User')->make()->toArray();
        unset($testUser['password']);
        $userRepository->create($testUser);

        $this->seeInDatabase('users',$testUser);
    }

    /*
     * @test
     */
    public function test_where_it_get_user()
    {
        //arrange
        $userRepository = $this->app->make('App\Http\Interfaces\UserInterface');
        $testUser = factory('App\Http\Models\User')->create()->toArray();

        $whereFilter = [
            'email'=>$testUser['email']
        ];

        //act
        $fromDatabaseUser = $userRepository->where($whereFilter)->toArray();

        //assert
        $this->assertEquals($testUser,$fromDatabaseUser);
    }
}

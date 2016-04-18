<?php
/**
 * Created by PhpStorm.
 * User: igor.talevski
 * Date: 6/19/2015
 * Time: 10:31 AM
 */

class SecurityTest extends TestCase{


    public function test_is_attempt_return_user_instance_on_success_login(){
        //arrange
        $userInDatabase = $this->createDummyUserInDatabase();

        $security = $this->app->make('Security');
        //dd($userInDatabase->toArray());

        $logedUser = $security->attempt($userInDatabase->email,'testpass');

        //dd($logedUser->toArray());
        $this->assertInstanceOf('App\Http\Models\User',$logedUser);
    }



    public function test_is_returning_boolean_false_if_no_login(){
        $security = $this->app->make('Security');
        $logedUser = $security->attempt('NO_USER_EXISTS','WRONG_PASS');
        $this->assertFalse($logedUser);
    }

}
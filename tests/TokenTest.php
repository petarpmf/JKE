<?php

/**
 * Created by PhpStorm.
 * User: igor.talevski
 * Date: 6/19/2015
 * Time: 2:31 PM
 */
class TokenTest extends TestCase
{


    public function test_token_generated_in_proper_format()
    {
        $token = \App\Http\Facades\Token::generate();
        $this->assertRegExp('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $token);
    }


    public function test_token_generated_and_saved_in_database()
    {
        $fakeUser = $this->createDummyUserInDatabase();
        $token = \Rhumsaa\Uuid\Uuid::uuid4();
        \App\Http\Facades\Token::save($token, $fakeUser);

        $this->seeInDatabase('tokens', ['token' => $token]);
    }


    public function test_token_generated_and_verify_ok()
    {
        $fakeUser = $this->createDummyUserInDatabase();
        $token = \App\Http\Facades\Token::generate();

        \App\Http\Facades\Token::save($token, $fakeUser);

        $this->assertInstanceOf('App\Http\Models\User', \App\Http\Facades\Token::verify($token));
    }

    public function test_token_generated_and_verify_to_fail()
    {
        $token = \Rhumsaa\Uuid\Uuid::uuid4();
        $this->assertFalse(\App\Http\Facades\Token::verify($token));
    }

    public function test_token_generated_and_destroy()
    {
        $fakeUser = $this->createDummyUserInDatabase();
        $token = \App\Http\Facades\Token::generate();
        \App\Http\Facades\Token::save($token, $fakeUser);


        \App\Http\Facades\Token::destroy($token);

        $this->assertFalse(\App\Http\Facades\Token::verify($token));
    }

}
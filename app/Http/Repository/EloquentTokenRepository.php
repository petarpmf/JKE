<?php
namespace App\Http\Repositories;

use App\Http\Interfaces\TokenInterface;
use App\Http\Models\Token;
use App\Http\Models\User;
use Rhumsaa\Uuid\Uuid;

class EloquentTokenRepository implements TokenInterface {

    /**
     * Generates unique token for specific user
     *
     * @return string
     */
    public function generate()
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * Saves generated token for specific user
     *
     * @param $token
     * @param $user
     * @return static
     */
    public function save($token, $user)
    {  //fix
       return Token::forceCreate(['token' => $token, 'user_id' => $user->id]);
       //return Token::forceCreate(['token' => $token, 'user_id' => $user->id, 'data' => serialize($user)]);
    }

    /**
     * Verify if token exists and is valid. Return user info if token is valid.
     *
     * @param $token
     * @return bool|mixed
     */
    public function verify($token)
    {
        $savedToken = Token::where(['token' => $token])->first();

        if ($savedToken === null) {
            return false;
        }
        //fix
        return User::find($savedToken->user_id);
        //return unserialize($savedToken->data);
    }

    /**
     * Delete the specified token
     *
     * @param $token
     * @return mixed
     */
    public function destroy($token)
    {
        return Token::where(['token' => $token])->delete();
    }

    public function getUserId($token)
    {
        $token = Token::where(['token' => $token])->first();

        if(count($token)>0){
            $token = $token->toArray();
            return $token['user_id'];
        }
        return "";
    }
}
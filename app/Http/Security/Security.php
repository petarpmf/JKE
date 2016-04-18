<?php
/**
 * Created by PhpStorm.
 * User: igor.talevski
 * Date: 6/18/2015
 * Time: 3:58 PM
 */
namespace App\Http\Security;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Facades\User;

class Security extends Controller
{
    /**
     * Attempt to login the user
     *
     * @param $username
     * @param $password
     * @return User instance or boolean false;
     */
    public function attempt($username, $password)
    {
        $user = User::where(['email' => $username]);
        if ($user) {
            if (Hash::check($password,$user->password)) {
                return $user;
            };
        }
        return false;
    }

}
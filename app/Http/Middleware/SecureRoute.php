<?php
namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Http\Facades\Token;
use App\Http\Facades\User;
use Closure;

class SecureRoute extends Controller
{

    /**
     * Run security filter on request
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //take the token from the Request (Header or Input)
        $token = $request->header('authorization');
        if ($token=="") {
            $token = $request->header('token', $request->input('token'));
        }
        //Admin
       $token='f97c880d-e61f-4eb4-96c7-3417d7f405d9';
        //Client
        //$token='94a595c2-f150-4ae0-9e99-5843b026e92a';
        //Inspector
        //$token='a9f3a1b1-fe71-40fe-b18b-4ba2a333c925';
        //check if the token is valid
        if ($token) {
            $user = Token::verify($token);
            if ($user) {
                $userId = User::setUserId($user);
                //set user_id as a request attribute
                $request->attributes->set('user_id', $userId);

                $request->attributes->set('user_role_id', $user->toArray()['role_id']);
                //set role_id
                ($user->toArray()['role_id'])=='1'?$request->attributes->set('role_id', $request->input('role_id', env('DEFAULT_ROLE_ID'))):$request->attributes->set('role_id', env('DEFAULT_ROLE_ID'));
                //check if user is logged as admin
                ($user->toArray()['role_id'])=='1'?$request->attributes->set('is_admin', true):$request->attributes->set('is_admin', false);
                //check if user is logged as client
                ($user->toArray()['role_id'])=='2'?$request->attributes->set('is_client', true):$request->attributes->set('is_client', false);
                //check if user is logged as inspector
                ($user->toArray()['role_id'])=='3'?$request->attributes->set('is_inspector', true):$request->attributes->set('is_inspector', false);
                return $next($request);
            }
        }

        return $this->responseUnauthorized();
    }
}

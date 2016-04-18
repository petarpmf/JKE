<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class UserDesiredJobProject extends Facade
{
    protected static function getFacadeAccessor(){
        return "UserDesiredJobProjectGateway";
    }
};
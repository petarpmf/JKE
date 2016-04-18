<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class Client extends Facade
{
    protected static function getFacadeAccessor(){
        return "ClientGateway";
    }
};
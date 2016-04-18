<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class Token extends Facade
{
    protected static function getFacadeAccessor(){
        return "TokenGateway";
    }
};
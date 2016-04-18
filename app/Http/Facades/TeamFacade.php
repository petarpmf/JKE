<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class Team extends Facade
{
    protected static function getFacadeAccessor(){
        return "TeamGateway";
    }
};
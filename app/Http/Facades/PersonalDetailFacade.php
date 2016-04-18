<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class PersonalDetail extends Facade
{
    protected static function getFacadeAccessor(){
        return "PersonalDetailGateway";
    }
};
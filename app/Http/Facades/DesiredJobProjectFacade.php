<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class DesiredJobProject extends Facade
{
    protected static function getFacadeAccessor(){
        return "DesiredJobProjectGateway";
    }
};
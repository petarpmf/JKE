<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class Innermetrix extends Facade
{
    protected static function getFacadeAccessor(){
        return "InnermetrixGateway";
    }
};
<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class Role extends Facade
{
    protected static function getFacadeAccessor(){
        return "RoleGateway";
    }
};
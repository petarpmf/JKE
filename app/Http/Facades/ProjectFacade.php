<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class Project extends Facade
{
    protected static function getFacadeAccessor(){
        return "ProjectGateway";
    }
};
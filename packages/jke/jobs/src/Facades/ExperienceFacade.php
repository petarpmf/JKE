<?php
namespace Jke\Jobs\Facades;

use Illuminate\Support\Facades\Facade;

class Experience extends Facade
{
    protected static function getFacadeAccessor(){
        return "ExperienceGateway";
    }
};
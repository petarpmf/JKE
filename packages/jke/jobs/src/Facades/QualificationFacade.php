<?php
namespace Jke\Jobs\Facades;

use Illuminate\Support\Facades\Facade;

class Qualification extends Facade
{
    protected static function getFacadeAccessor(){
        return "QualificationGateway";
    }
};
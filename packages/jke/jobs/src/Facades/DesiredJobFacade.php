<?php
namespace Jke\Jobs\Facades;

use Illuminate\Support\Facades\Facade;

class DesiredJob extends Facade
{
    protected static function getFacadeAccessor(){
        return "DesiredJobGateway";
    }
};
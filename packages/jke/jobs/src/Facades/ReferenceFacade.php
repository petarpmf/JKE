<?php
namespace Jke\Jobs\Facades;

use Illuminate\Support\Facades\Facade;

class Reference extends Facade
{
    protected static function getFacadeAccessor(){
        return "ReferenceGateway";
    }
};
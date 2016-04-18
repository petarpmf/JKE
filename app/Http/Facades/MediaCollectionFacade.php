<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class MediaCollection extends Facade
{
    protected static function getFacadeAccessor(){
        return "MediaCollectionGateway";
    }
};
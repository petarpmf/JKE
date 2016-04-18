<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class Notify extends Facade
{
    protected static function getFacadeAccessor(){
        return "NotifyService";
    }
};
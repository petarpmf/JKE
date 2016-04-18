<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class ScoringTemplate extends Facade
{
    protected static function getFacadeAccessor(){
        return "ScoringTemplateGateway";
    }
};
<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class Scoring extends Facade
{
    protected static function getFacadeAccessor(){
        return "ScoringGateway";
    }
};
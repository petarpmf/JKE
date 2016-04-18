<?php
namespace App\Http\Facades;

use Illuminate\Support\Facades\Facade;

class ReferenceQualification extends Facade
{
    protected static function getFacadeAccessor(){
        return "ReferenceQualificationGateway";
    }
};
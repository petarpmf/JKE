<?php
namespace Jke\Jobs\Facades;

use Illuminate\Support\Facades\Facade;

class Certificate extends Facade
{
    protected static function getFacadeAccessor(){
        return "CertificateGateway";
    }
};
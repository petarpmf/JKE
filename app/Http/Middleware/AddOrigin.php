<?php
namespace App\Http\Middleware;

use Closure;

/**
 * User: igor.talevski@it-labs.com
 * Date: 6/29/2015
 * Time: 3:23 PM
 */

class AddOrigin
{
    public function handle($request, Closure $next)
    {

        $response = $next($request);
        $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
        $response->header('Access-Control-Allow-Origin', '*');

        // header_remove("X-Powered-By");
        $response->header('X-Powered-By','Pe a Pe Team @ http://www.IT-Labs.com/');

        return $response;
    }
}
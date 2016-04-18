<?php
use Symfony\Component\HttpFoundation\Response;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv::load(__DIR__ . '/../');

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    'Illuminate\Contracts\Debug\ExceptionHandler',
    'App\Exceptions\Handler'
);

$app->singleton(
    'Illuminate\Contracts\Console\Kernel',
    'App\Console\Kernel'
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     // 'Illuminate\Cookie\Middleware\EncryptCookies',
//     // 'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
//     // 'Illuminate\Session\Middleware\StartSession',
//     // 'Illuminate\View\Middleware\ShareErrorsFromSession',
//     // 'Laravel\Lumen\Http\Middleware\VerifyCsrfToken',
// ]);

$app->routeMiddleware([
        'SecureRoute' => 'App\Http\Middleware\SecureRoute',
        'CheckRoute' => 'App\Http\Middleware\CheckRoute',
        'AddOrigin' => 'App\Http\Middleware\AddOrigin'
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register('App\Providers\AppServiceProvider');

//$app->register(Itlabs\Lessons\LessonsServiceProvider::class);
//$app->register(Itlabs\Security\SecurityServiceProvider::class);
$app->register(App\Providers\VendorPublishServiceProvider::class);
$app->register(App\Providers\MediaServiceProvider::class);

//$app->register(Itlabs\Notify\NotifyServiceProvider::class);

$app->bind('Security','App\Http\Security\Security');
$app->bind('TokenGateway','App\Http\Gateways\TokenGateway');
$app->bind('UserGateway','App\Http\Gateways\UserGateway');
$app->bind('RoleGateway','App\Http\Gateways\RoleGateway');
$app->bind('NotifyService','App\Http\Services\NotifyService');
$app->bind('MediaCollectionGateway','App\Http\Gateways\MediaCollectionGateway');
$app->bind('MediaGateway','App\Http\Gateways\MediaGateway');
$app->bind('DashboardGateway','App\Http\Gateways\DashboardGateway');
$app->bind('CompanyGateway','App\Http\Gateways\CompanyGateway');
$app->bind('ProjectGateway','App\Http\Gateways\ProjectGateway');
$app->bind('DesiredJobProjectGateway','App\Http\Gateways\DesiredJobProjectGateway');
$app->bind('UserDesiredJobProjectGateway','App\Http\Gateways\UserDesiredJobProjectGateway');
$app->bind('PersonalDetailGateway','App\Http\Gateways\PersonalDetailGateway');
$app->bind('TeamGateway','App\Http\Gateways\TeamGateway');
$app->bind('InnermetrixGateway','App\Http\Gateways\InnermetrixGateway');
$app->bind('ClientGateway','App\Http\Gateways\ClientGateway');
$app->bind('ScoringTemplateGateway','App\Http\Gateways\ScoringTemplateGateway');
$app->bind('ScoringGateway','App\Http\Gateways\ScoringGateway');
$app->bind('ReferenceQualificationGateway','App\Http\Gateways\ReferenceQualificationGateway');

$app->bind('App\Http\Interfaces\TokenInterface','App\Http\Repositories\EloquentTokenRepository');
$app->bind('App\Http\Interfaces\UserInterface','App\Http\Repositories\EloquentUserRepository');
$app->bind('App\Http\Interfaces\RoleInterface','App\Http\Repositories\EloquentRoleRepository');
$app->bind('App\Http\Interfaces\MediaCollectionInterface','App\Http\Repositories\EloquentMediaCollectionRepository');
$app->bind('App\Http\Interfaces\MediaInterface','App\Http\Repositories\EloquentMediaRepository');
$app->bind('App\Http\Interfaces\DashboardInterface','App\Http\Repositories\EloquentDashboardRepository');
$app->bind('App\Http\Interfaces\CompanyInterface','App\Http\Repositories\EloquentCompanyRepository');
$app->bind('App\Http\Interfaces\ProjectInterface','App\Http\Repositories\EloquentProjectRepository');
$app->bind('App\Http\Interfaces\DesiredJobProjectInterface','App\Http\Repositories\EloquentDesiredJobProjectRepository');
$app->bind('App\Http\Interfaces\UserDesiredJobProjectInterface','App\Http\Repositories\EloquentUserDesiredJobProjectRepository');
$app->bind('App\Http\Interfaces\PersonalDetailInterface','App\Http\Repositories\EloquentPersonalDetailRepository');
$app->bind('App\Http\Interfaces\TeamInterface','App\Http\Repositories\EloquentTeamRepository');
$app->bind('App\Http\Interfaces\InnermetrixInterface','App\Http\Repositories\EloquentInnermetrixRepository');
$app->bind('App\Http\Interfaces\ClientInterface','App\Http\Repositories\EloquentClientRepository');
$app->bind('App\Http\Interfaces\ScoringTemplateInterface','App\Http\Repositories\EloquentScoringTemplateRepository');
$app->bind('App\Http\Interfaces\ScoringInterface','App\Http\Repositories\EloquentScoringRepository');
$app->bind('App\Http\Interfaces\ReferenceQualificationInterface','App\Http\Repositories\EloquentReferenceQualificationRepository');

//$app->register(Jke\Security\SecurityServiceProvider::class);
$app->register(Jke\Jobs\JobsServiceProvider::class);
$app->register(App\Providers\SendEmailServiceProvider::class);
//$app->bind('Token','Itlabs\Security\Token');
//class_alias('Itlabs\Security\Token','Token');

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$r = $app->make('request');

//dd($r->header('origin'));
if ($r->getMethod() == "OPTIONS") {

    $response = new \Illuminate\Http\Response;


    //response("",200,[]);
    $headers = [
        'Access-Control-Allow-Methods' => 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS',
        //'Access-Control-Allow-Headers' => 'Authorization',
        //'Access-Control-Allow-Headers' => 'Accept',
        'Access-Control-Allow-Headers' => 'accept, authorization, content-type',

        'Access-Control-Allow-Credentials' => true,
        'Access-Control-Allow-Origin' =>$r->header('origin'),
        'X-Powered-By' => 'Pe a Pe Team @ http://www.IT-Labs.com/'
    ];
    $app->abort(200, "Take a lok at: bootstrap/app.php, This is special response for AngularJS OPTION request", $headers);

    //return $response->;
    //return new SuccessResponse();
}


require __DIR__ . '/../app/Http/routes.php';

return $app;

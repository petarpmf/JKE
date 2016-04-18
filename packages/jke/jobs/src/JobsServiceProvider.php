<?php
namespace Jke\Jobs;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

/**
 * Created by PhpStorm.
 * User: igor.talevski
 * Date: 6/18/2015
 * Time: 11:34 AM
 */
class JobsServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';

        //$this->setConnection();

        $this->publishes([
            realpath(__DIR__ . '/../database/migrations') => $this->app->databasePath() . '/migrations',
        ]);

        $this->app->routeMiddleware(
            [
                'SecureRoute' => 'App\Http\Middleware\SecureRoute',
                'AddOrigin' => 'App\Http\Middleware\AddOrigin'
            ]
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //Desired jobs
        $this->app->bind('DesiredJobGateway','Jke\Jobs\Gateways\DesiredJobGateway');
        $this->app->bind('Jke\Jobs\Interfaces\DesiredJobInterface','Jke\Jobs\Repository\EloquentDesiredJobRepository');
        //Experiences
        $this->app->bind('ExperienceGateway','Jke\Jobs\Gateways\ExperienceGateway');
        $this->app->bind('Jke\Jobs\Interfaces\ExperienceInterface','Jke\Jobs\Repository\EloquentExperienceRepository');
        //Qualifications
        $this->app->bind('QualificationGateway','Jke\Jobs\Gateways\QualificationGateway');
        $this->app->bind('Jke\Jobs\Interfaces\QualificationInterface','Jke\Jobs\Repository\EloquentQualificationRepository');
        //Qualifications
        $this->app->bind('CertificateGateway','Jke\Jobs\Gateways\CertificateGateway');
        $this->app->bind('Jke\Jobs\Interfaces\CertificateInterface','Jke\Jobs\Repository\EloquentCertificateRepository');

        //Qualifications
        $this->app->bind('ReferenceGateway','Jke\Jobs\Gateways\ReferenceGateway');
        $this->app->bind('Jke\Jobs\Interfaces\ReferenceInterface','Jke\Jobs\Repository\EloquentReferenceRepository');
    }

    /**
     * Setup connection for this package
     *
     * @return void
     */
    /*
    private function setConnection()
    {
        $DatabaseConfig = [
            'driver' => 'mysql',
            'host' => env('DB_JOBS_HOST', 'localhost'),
            'port' => env('DB_JOBS_PORT', 3306),
            'database' => env('DB_JOBS_DATABASE', 'forge'),
            'username' => env('DB_JOBS_USERNAME', 'forge'),
            'password' => env('DB_JOBS_PASSWORD', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => env('DB_PREFIX', ''),
            'timezone' => env('DB_TIMEZONE', '+00:00'),
            'strict' => false,
        ];

        Config::set('database.connections.jobs', $DatabaseConfig);
    }
    */
}
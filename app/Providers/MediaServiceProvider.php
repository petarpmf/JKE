<?php
namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $S3Config = [
            'bucket' => env('S3_BUCKET', ''),
            'AccessKey' => env('S3_KEY', ''),
            'Region' => env('S3_REGION', ''),
            'SecretAccessKey' => env('S3_SECRET', ''),
            'CloudeFrontUrl' => env('S3_CLOUDFRONT', ''),
        ];

        Config::set('aws.connections', $S3Config);
        Config::set('media.default_folder', 'uploads');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }
}
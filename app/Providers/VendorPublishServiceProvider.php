<?php
/**
 * Created by PhpStorm.
 * User: igor.talevski
 * Date: 6/18/2015
 * Time: 1:28 PM
 */

namespace App\Providers;



use App\Console\Commands\VendorPublishCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class VendorPublishServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.vendor.command', function()
        {
            return new VendorPublishCommand(new Filesystem());
        });

        $this->commands(
            'command.vendor.command'
        );
    }
}
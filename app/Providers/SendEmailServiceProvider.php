<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\SendEmailCommand;

class SendEmailServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.email.command', function()
        {
            return new SendEmailCommand;
        });

        $this->commands(
            'command.email.command'
        );
    }
}
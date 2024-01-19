<?php

namespace App\Providers;

use Twilio\Rest\Client as Twilio;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Twilio::class, function () {
            $sid = Config::get('services.twilio.sid');
            $token = Config::get('services.twilio.token');
            return new Twilio($sid, $token);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

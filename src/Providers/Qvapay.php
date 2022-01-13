<?php

namespace Ovillafuerte94\QvapayLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use Ovillafuerte94\QvapayLaravel\ApiClient;

class Qvapay extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ApiClient::class, function () {
            $defaultConfig = [
                'app_id' => config('qvapay.app_id'),
                'app_secret' => config('qvapay.app_secret')
            ];

            return new ApiClient($defaultConfig);
        });
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/qvapay.php', 'qvapay');

        $this->publishes([
            __DIR__ . '/../../config/qvapay.php' => config_path('qvapay.php'),
        ]);
    }
}

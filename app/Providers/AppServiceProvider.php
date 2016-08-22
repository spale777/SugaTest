<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use App\Services\SugarConnectorService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Injects a SugarConnectorService into a service container accessible application wide
         * and passes Guzzle Client as dependency
         */

        $this->app->singleton('SugaConnector', function(){
            return new SugarConnectorService(
                new Client,
                env('SUGA_BASE_URL'),
                env('SUGA_GET_TOKEN_URL'),
                env('SUGA_USERNAME'),
                env('SUGA_PASSWORD'),
                env('SUGA_CLIENT_ID'),
                env('SUGA_PLATFORM'),
                env('SUGA_GRANT_TYPE')
            );
        });
    }
}

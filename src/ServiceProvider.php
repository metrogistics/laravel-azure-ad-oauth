<?php

namespace Metrogistics\AzureSocialite;

use Illuminate\Support\Facades\Auth;
use SocialiteProviders\Manager\SocialiteWasCalled;
use Metrogistics\AzureSocialite\Middleware\Authenticate;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/azure-oauth.php' => config_path('azure-oauth.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/config/azure-oauth.php', 'azure-oauth'
        );

        $this->app['Laravel\Socialite\Contracts\Factory']->extend('azure-oauth', function($app){
            return $app['Laravel\Socialite\Contracts\Factory']->buildProvider(
                'Metrogistics\AzureSocialite\AzureOauthProvider',
                config('azure-oauth.credentials')
            );
        });

        $this->app['router']->group(['middleware' => config('azure-oauth.routes.middleware')], function($router){
            $router->get(config('azure-oauth.routes.login'), 'Metrogistics\AzureSocialite\AuthController@redirectToOauthProvider');
            $router->get(config('azure-oauth.routes.callback'), 'Metrogistics\AzureSocialite\AuthController@handleOauthResponse');
        });
    }
}

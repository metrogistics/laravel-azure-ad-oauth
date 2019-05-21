<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

Route::group(['middleware' => Config::get('azure-oath.routes.middleware')], function() {
    Route::get(Config::get('azure-oath.routes.login'), 'Metrogistics\AzureSocialite\AuthController@redirectToOauthProvider');
    Route::get(Config::get('azure-oath.routes.callback'), 'Metrogistics\AzureSocialite\AuthController@handleOauthResponse');
});
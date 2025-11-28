<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Support\Facades\Route;

Route::get('/', ['as' => 'login', 'uses' => 'App\Http\Controllers\Auth\WebRouterController@index']);

Route::group(['prefix' => 'auth', 'middleware' => HandleInertiaRequests::class], function () {
    Route::get('/', ['as' => 'login', 'uses' => 'App\Http\Controllers\Auth\WebRouterController@index']);
    Route::get('logout', ['as' => 'auth.logout', 'uses' => 'App\Http\Controllers\Auth\Api\AuthController@logout']);
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth:sanctum', HandleInertiaRequests::class]], function () {
    Route::get('/', ['as' => 'dashboard.index', 'uses' => 'App\Http\Controllers\Dashboard\WebRouterController@index']);

    Route::group(['prefix' => 'evaluations', 'middleware' => ['auth:sanctum', HandleInertiaRequests::class]], function () {
        Route::get('create', ['as' => 'dashboard.evaluations.create_index', 'uses' => 'App\Http\Controllers\Dashboard\WebRouterController@createIndex']);
    });
});

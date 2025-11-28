<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'middleware' => 'web'], function () {
    Route::post('login', ['as' => 'auth.login', 'uses' => 'App\Http\Controllers\Auth\Api\AuthController@login']);
});

Route::group(['prefix' => 'evaluations', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', ['as' => 'evaluations.list', 'uses' => 'App\Http\Controllers\Dashboard\Api\EvaluationsController@list']);
    Route::get('/{id}', ['as' => 'evaluations.read', 'uses' => 'App\Http\Controllers\Dashboard\Api\EvaluationsController@show']);
    Route::post('store', ['as' => 'evaluations.store', 'uses' => 'App\Http\Controllers\Dashboard\Api\EvaluationsController@store']);
    Route::put('/{id}', ['as' => 'evaluations.update', 'uses' => 'App\Http\Controllers\Dashboard\Api\EvaluationsController@update']);
    Route::delete('/{id}', ['as' => 'evaluations.delete', 'uses' => 'App\Http\Controllers\Dashboard\Api\EvaluationsController@delete']);
});

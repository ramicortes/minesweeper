<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('signup', 'Api\AuthController@signUp');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
        Route::post('game/new', 'Api\GameController@store');
    });
});

Route::group([
    'prefix' => 'game'
], function () {
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::post('new', 'Api\GameController@store');
        Route::get('{game}', 'Api\GameController@show')->middleware('own');
        Route::post('{game}/cell/{cell}/uncover', 'Api\GameController@uncover')->middleware('own');
        Route::post('{game}/cell/{cell}/flag', 'Api\GameController@flag')->middleware('own');
    });
});

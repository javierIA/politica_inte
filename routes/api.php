<?php

use Illuminate\Http\Request;

// Route::get('/test', 'Api\AuthController@test');


Route::group([], function () {
    Route::get('/test', 'API\AuthController@test');
});

Route::post('/signup', 'Api\AuthController@signup');
/*Route::post('/login', 'Api\AuthController@login');

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('signup', 'Api\AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
    });
});*/


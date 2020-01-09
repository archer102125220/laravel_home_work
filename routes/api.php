<?php

use Illuminate\Http\Request;

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
Route::group(['middleware' => 'cors'], function () {
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@login');
    Route::group(['middleware' => 'jwtAuth'], function () {
        Route::group([ 'prefix' => 'user',], function () {
            Route::get('getThisUserData', 'UserController@getThisUserData');
            Route::match(['put', 'patch'], 'edit/{userId}', 'UserController@editUser');
            Route::post('new_user', 'UserController@register');
            Route::delete('delete_user', 'UserController@deleteUser');
        });
        Route::group([ 'prefix' => 'comment',], function () {
            Route::post('new_comment', 'commentController@newComment');
            Route::match(['put', 'patch'], 'edit/{comment_id}', 'commentController@editComment');
        });
    });
});
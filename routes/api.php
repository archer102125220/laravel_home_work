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
Route::group([
        'middleware' => 'cors',
    ], function ($router) {
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@login');
    Route::group(['middleware' => 'jwtAuth'], function () {
        Route::get('user/getThisUserData', 'UserController@getThisUserData');
        Route::match(['put', 'patch'], 'user/edit/{userId}', 'UserController@editUser');
        Route::post('user/new_user', 'UserController@register');
        Route::delete('user/delete_user', 'UserController@deleteUser');
    
        Route::post('comment/new_comment', 'commentController@newComment');
        Route::match(['put', 'patch'], 'comment/edit/{comment_id}', 'commentController@editComment');
    });
});
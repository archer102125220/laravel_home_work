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

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::group(['middleware' => 'jwtAuth'], function () {
    Route::get('getUserData', 'UserController@getUserData');
    Route::match(['put', 'patch'], 'user/edit/{userId}', 'UserController@editUser');
    Route::post('comment', 'commentController@comment');
});
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
            Route::get('user_all', 'UserController@userAll');
            Route::match(['put', 'patch'], 'edit/{userId}', 'UserController@editUser');
            Route::post('new_user', 'UserController@register');
            Route::delete('delete_user', 'UserController@deleteUser');
        });
        Route::group([ 'prefix' => 'post',], function () {
            Route::get('posts', 'PostController@Posts');
            Route::get('posts/{postsId}', 'PostController@Post');
            Route::post('new_post', 'PostController@newPost');
            Route::match(['put', 'patch'], 'edit/{postsId}', 'PostController@editPost');
            Route::delete('delete_post', 'PostController@deletePost');
        });
        Route::group([ 'prefix' => 'comment',], function () {
            Route::get('comments', 'CommentController@CommentAll');
            Route::get('comments/{commentId}', 'CommentController@Comment');
            Route::get('comments/postsId/{postsId}', 'CommentController@CommentByPostsId');
            Route::post('new_comment', 'commentController@newComment');
            Route::match(['put', 'patch'], 'edit/{commentId}', 'commentController@editComment');
            Route::delete('delete_comment', 'commentController@deleteComment');
        });
    });
});
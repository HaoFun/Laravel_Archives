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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->get('/topics','TopicsController@getTopics');

//Archive follow route
Route::middleware('auth:api')->post('/archive/follower','ArchivesFollowController@follower');
Route::middleware('auth:api')->post('/archive/follow','ArchivesFollowController@followThis');


//User follow route
Route::middleware('auth:api')->post('/user/followers','FollowersController@index');
Route::middleware('auth:api')->post('/user/follow','FollowersController@follow');
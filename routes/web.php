<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','ArchivesController@index');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

//email verify route
Route::get('email/verify/{token}','EmailController@verify')->name('email.verify');

//Archives route
Route::resource('/archives','ArchivesController');

//Answers route
Route::post('/archives/{archive}/answer','AnswersController@store')->name('answer.store');
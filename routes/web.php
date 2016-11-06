<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', 'HomeController@index');

Route::get('/account', ['uses' => 'AccountController@index', 'middleware' => 'auth']);

Route::get('/activities', ['uses' => 'ActivitiesController@index', 'middleware' => 'auth']);

Route::get('/activities/add', ['uses' => 'ActivitiesController@add', 'middleware' => 'auth']);
Route::post('/activities/add', ['uses' => 'ActivitiesController@addACTION', 'middleware' => 'auth']);

Route::get('/activities/overview/{id}', ['uses' => 'ActivitiesController@overview', 'middleware' => 'auth']);

Route::get('/activities/signup/{id}', ['uses' => 'ActivitiesController@signup', 'middleware' => 'auth']);
Route::post('/activities/signup/{id}', ['uses' => 'ActivitiesController@signupACTION', 'middleware' => 'auth']);



Route::get('/', 'HomeController@index');

/* AUTH ROUTES */ 
Route::get('register', ['as' => 'auth.register', 'uses' => 'Auth\LoginController@showRegistrationForm']);
Route::post('register', ['as' => 'auth.register', 'uses' => 'Auth\LoginController@register']);
Route::get('login', ['as' => 'auth.login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['as' => 'auth.login', 'uses' => 'Auth\LoginController@login']);
Route::get('logout', ['as' => 'auth.logout', 'uses' => 'Auth\LoginController@logout']);
/* END AUTH ROUTES */



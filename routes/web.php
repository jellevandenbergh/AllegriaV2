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

Route::get('/account', ['uses' => 'AccountController@index', 'middleware' => 'lid']);

Route::get('/account/edit', ['uses' => 'AccountController@edit_account', 'middleware' => 'lid']);
Route::post('/account/edit', ['uses' => 'AccountController@edit_accountACTION', 'middleware' => 'lid']);

Route::get('/activities', ['uses' => 'ActivitiesController@index', 'middleware' => 'lid']);

Route::get('/activities/add', ['uses' => 'ActivitiesController@add', 'middleware' => 'admin']);
Route::post('/activities/add', ['uses' => 'ActivitiesController@addACTION', 'middleware' => 'admin']);

Route::get('/activities/overview/{activity_id}', ['uses' => 'ActivitiesController@overview', 'middleware' => 'lid']);

Route::get('/activities/signup/{activity_id}', ['uses' => 'ActivitiesController@signup', 'middleware' => 'lid']);
Route::post('/activities/signup/{activity_id}', ['uses' => 'ActivitiesController@signupACTION', 'middleware' => 'lid']);

Route::get('/unauthorized', 'HomeController@unauthorized');

Route::get('/', 'HomeController@index');

/* AUTH ROUTES */ 
Route::get('register', 'Auth\Registercontroller@showRegistrationForm');
Route::post('register', 'Auth\Registercontroller@register');
Route::get('login', ['as' => 'auth.login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['as' => 'auth.login', 'uses' => 'Auth\LoginController@login']);
Route::get('logout', ['as' => 'auth.logout', 'uses' => 'Auth\LoginController@logout']);
/* END AUTH ROUTES */



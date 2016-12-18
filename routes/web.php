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


/* HOME ROUTE */
Route::get('/home', 'HomeController@index');

/* ACCOUNT ROUTES */
Route::get('/account', ['uses' => 'AccountController@index', 'middleware' => 'lid']);
Route::get('/account/edit', ['uses' => 'AccountController@edit_account', 'middleware' => 'lid']);
Route::post('/account/edit', ['uses' => 'AccountController@edit_accountACTION', 'middleware' => 'lid']);
Route::get('/account/editpassword', ['uses' => 'AccountController@editpassword', 'middleware' => 'lid']);
Route::post('/account/editpassword', ['uses' => 'AccountController@editpasswordACTION', 'middleware' => 'lid']);
/* END ACCOUNT ROUTES */

Route::get('/forgotpassword', 'MembersController@forgot_password');
Route::post('/forgotpassword', 'MembersController@forgot_passwordACTION');
Route::get('/forgotpassword/{token}', 'MembersController@reset_password_by_token');
Route::post('/forgotpassword/{token}', 'MembersController@reset_password_by_tokenACTION');

/* ACTIVITIES ROUTES */
Route::get('/activities', ['uses' => 'ActivitiesController@index', 'middleware' => 'lid']);
Route::get('/activities/add', ['uses' => 'ActivitiesController@add', 'middleware' => 'admin']);
Route::post('/activities/add', ['uses' => 'ActivitiesController@addACTION', 'middleware' => 'admin']);
Route::post('/activities/delete/{activity_id}', ['uses' => 'ActivitiesController@deleteACTION', 'middleware' => 'admin']);
Route::get('/activities/delete/{activity_id}', ['uses' => 'ActivitiesController@delete', 'middleware' => 'admin']);
Route::get('/activities/overview/{activity_id}', ['uses' => 'ActivitiesController@overview', 'middleware' => 'lid']);
Route::get('/activities/signup/{activity_id}', ['uses' => 'ActivitiesController@signup', 'middleware' => 'lid']);
Route::post('/activities/signup/{activity_id}', ['uses' => 'ActivitiesController@signupACTION', 'middleware' => 'lid']);
Route::get('/activities/confirmsignup/{token}', ['uses' => 'ActivitiesController@confirmsignupACTION', 'middleware' => 'lid']);
/* END ACTIVITIES ROUTES */

/* MEMBERS ROUTES */
Route::get('members', ['uses' => 'MembersController@index', 'middleware' => 'admin']);
Route::get('members/getmembers', ['uses' => 'MembersController@getmembers', 'middleware' => 'admin']);
Route::get('members/edit/{member_id}', ['uses' => 'MembersController@edit', 'middleware' => 'admin']);
Route::post('members/edit/{member_id}', ['uses' => 'MembersController@editACTION', 'middleware' => 'admin']);
Route::get('members/delete/{member_id}', ['uses' => 'MembersController@delete', 'middleware' => 'admin']);
Route::post('members/delete/{member_id}', ['uses' => 'MembersController@deleteACTION', 'middleware' => 'admin']);
Route::get('members/add', ['uses' => 'MembersController@add', 'middleware' => 'admin']);
Route::post('members/add', ['uses' => 'MembersController@addACTION', 'middleware' => 'admin']);
Route::get('/account/newuser/{token}', ['uses' => 'MembersController@activatemember']);
Route::post('/account/newuser/{token}', ['uses' => 'MembersController@activatememberACTION']);
Route::get('members/sendverification/{member_id}', ['uses' => 'MembersController@sendverification', 'middleware' => 'admin']);
Route::post('members/sendverification/{member_id}', ['uses' => 'MembersController@sendverificationACTION', 'middleware' => 'admin']);
/* END MEMBERS ROUTES */

Route::get('/activities', ['uses' => 'ActivitiesController@index', 'middleware' => 'admin']);

/* UNAUTHORIZED ROUTE */
Route::get('/unauthorized', 'HomeController@unauthorized');


/* AUTH ROUTES */ 
Route::get('register', 'Auth\Registercontroller@showRegistrationForm');
Route::post('register', 'Auth\Registercontroller@register');
Route::get('login', ['as' => 'auth.login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::get('/', ['as' => 'auth.login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['as' => 'auth.login', 'uses' => 'Auth\LoginController@login']);
Route::get('logout', ['as' => 'auth.logout', 'uses' => 'Auth\LoginController@logout']);
/* END AUTH ROUTES */



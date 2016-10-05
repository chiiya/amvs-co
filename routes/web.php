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

Route::get('/', function() {
    return view('index');
});

Route::post('auth/login', 'AuthController@postLogin');

/***
 * API - ROUTES
 */

/* GET find a user: /api/users/ */
Route::get('api/users', 'UserController@find');
/* GET a user's info: /api/users/{id}/ */
Route::get('api/users/{id}', 'UserController@show');
/* POST a new user: /api/users/ */
Route::post('api/users', 'UserController@store');

/* GET all AMVS of a user / find an amv: /api/users/{id}/amvs/ */
Route::get('api/amvs', 'AMVController@index');
/* GET a specific AMV of a user: /api/users/{id}/amvs/{id}/ */
Route::get('api/amvs/{id}', 'AMVController@show');
/* POST a new AMV: /api/users/{id}/amvs/ */
Route::post('api/amvs', 'AMVController@store');
/* PUT update an existing AMV: /api/users/{id}/amvs/{id}/ */
Route::put('api/amvs/{id}', 'AMVController@update');
/* DELETE an existing AMV: /api/users/{id}/amvs/{id}/ */
Route::delete('api/amvs/{id}', 'AMVController@destroy');

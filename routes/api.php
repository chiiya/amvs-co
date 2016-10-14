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

Route::post('/auth/token', 'AuthController@authenticate');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

/* GET find a user: /api/users/ */
Route::get('/users', 'ApiUserController@find');
/* GET a user's info: /api/users/{id}/ */
Route::get('/users/{id}', 'ApiUserController@show');
/* POST a new user: /api/users/ */
Route::post('/users', 'ApiUserController@store');

/* GET all AMVS of a user / find an amv: /api/amvs/ */
Route::get('/amvs', 'AMVController@index');
/* GET a specific AMV: /api/amvs/{id}/ */
Route::get('/amvs/{id}', 'AMVController@show');
/* POST a new AMV: /api/amvs/ */
Route::post('/amvs', 'AMVController@store')->middleware('auth:api');
/* PUT update an existing AMV: /api/amvs/{id}/ */
Route::put('/amvs/{id}', 'AMVController@update')->middleware('auth:api');
/* DELETE an existing AMV: /api/amvs/{id}/ */
Route::delete('/amvs/{id}', 'AMVController@destroy')->middleware('auth:api');

<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
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
Route::get('/amvs', 'ApiAMVController@index');
/* GET a specific AMV: /api/amvs/{id}/ */
Route::get('/amvs/{id}', 'ApiAMVController@show');
/* POST a new AMV: /api/amvs/ */
Route::post('/amvs', 'ApiAMVController@store')->middleware('auth:api');
/* PUT update an existing AMV: /api/amvs/{id}/ */
Route::put('/amvs/{id}', 'ApiAMVController@update')->middleware('auth:api');
/* DELETE an existing AMV: /api/amvs/{id}/ */
Route::delete('/amvs/{id}', 'ApiAMVController@destroy')->middleware('auth:api');


Route::get('/genres', function() {
    return response()->json(App\Genre::all());
});

Route::get('/contests', function() {
    return response()->json(App\Contest::all());
});

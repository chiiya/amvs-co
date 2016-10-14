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
    if (Auth::check()) return redirect()->route('profile');
    else return view('index');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', 'UserController@showProfile')->name('profile');
});

Route::get('/user/{name}', 'UserController@show');

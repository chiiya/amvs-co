<?php

use App\Genre;
use App\Contest;

/**
* Page Routes
* -----------------------------------------------------------------------------------------------------
*/

// Make view name available to all Blade layouts
View::composer('*', function ($view) {
    $viewName = $view->getName();
    // If view starts with a number (404, 503, ...) prefix with error for valid css classname
    if (strpos($viewName, '404') !== false){
        $viewName = 'error404';
    }
    View::share('viewName', $viewName);
});

Route::get('/', function() {
    if (Auth::check()) return redirect()->route('dashboard');
    else return redirect()->route('login');
})->name('index');

Route::get('/login', 'PagesController@showLogin')->name('login');
Route::get('/signup', 'PagesController@showSignup');

Route::get('/user/{name}', 'PagesController@showUser');
Route::get('/user/{name}/{amv}', 'PagesController@showAMV');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'PagesController@showDashboard')->name('dashboard');
    Route::get('/dashboard/{all}', 'PagesController@showDashboard')
        ->where(['all' => '.*']);
});


/**
* Session API Routes
* -----------------------------------------------------------------------------------------------------
*/

Route::group(['prefix' => 'api'], function () {

    // Ressource: User
    Route::get('/users/{id}', 'UserController@show');
    Route::post('/users', 'UserController@store');
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/user', 'UserController@auth');
        Route::put('/users/{id}', 'UserController@update');
        Route::delete('/users/{id}', 'UserController@destroy');
    });

    // Ressource: AMV
    Route::get('/amvs', 'AMVController@index');
    Route::get('/amvs/{id}', 'AMVController@show');
    Route::group(['middleware' => 'auth'], function () {
        Route::post('/amvs', 'AMVController@store');
        Route::put('/amvs/{id}', 'AMVController@update');
        Route::delete('/amvs/{id}', 'AMVController@destroy');
    });

    // Ressource: Award
    Route::group(['middleware' => 'auth'], function () {
        Route::post('/awards', 'AwardController@store');
        Route::put('/awards/{id}', 'AwardController@update');
        Route::delete('/awards/{id}', 'AwardController@destroy');
    });

    // Ressource: Like
    Route::group(['middleware' => 'auth'], function() {
        Route::post('/likes', 'LikeController@store');
        Route::delete('/likes/{id}', 'LikeController@destroy');
    });
    
    // Ressource: Genre
    Route::get('/genres', function() {
        return response()->json(Genre::all(), 200);
    });

    // Ressource: Contest
    Route::get('/contests', function() {
        return response()->json(Contest::all(), 200);
    });
});

/**
* Auth Routes
* -----------------------------------------------------------------------------------------------------
*/

// Authentication Routes...
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Registration Routes...
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

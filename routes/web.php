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

Route::get('/login', function() {
    if (Auth::check()) return redirect()->route('dashboard');
    else return view('login');
})->name('login');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'UserController@showDashboard')->name('dashboard');
    Route::get('/profile', 'UserController@showProfile')->name('profile');
    
    Route::put('/amv/{amvId}/awards', 'AMVController@updateAwards');
    Route::delete('/amv/{amvId}/awards/{awardId}', 'AMVController@deleteAward');
});

Route::get('/user/{name}', 'UserController@show');

Route::get('user/{name}/{amv}', 'AMVController@show');

 // Authentication Routes...
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

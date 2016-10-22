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

// Ajax
Route::group(['namespace' => 'Ajax'], function() {

});

// Facebook
Route::group(['namespace' => 'Facebook'], function() {
    Route::get('/login', 'LoginController@login')->name('login');
    Route::get('/logout', 'LoginController@logout')->name('logout');
});


// Home
Route::get('/', 'HomeController@index')->name('home');

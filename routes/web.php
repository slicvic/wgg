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

Route::group(['namespace' => 'Account'], function() {
    Route::get('/account/events', 'EventsController@index')->name('account.events.index');
});

// Events
// Route::get('/events/{id}', 'EventsController@show')->name('events.show')->where('id', '[0-9]+');
Route::get('/events/create', 'EventsController@create')->name('events.create');
Route::post('/events', 'EventsController@store')->name('events.store');
Route::get('/events/{id}/edit', 'EventsController@edit')->name('events.edit')->where('id', '[0-9]+');
Route::post('/events/{id}', 'EventsController@update')->name('events.update')->where('id', '[0-9]+');
Route::get('/events/{id}/cancel', 'EventsController@cancel')->name('events.cancel')->where('id', '[0-9]+');

// Home
Route::get('/', 'HomeController@index')->name('home');

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

// Login
Route::get('/login/facebook', 'LoginController@facebook')->name('login.facebook');
Route::get('/logout', 'LoginController@logout')->name('logout');

// User
Route::group(['namespace' => 'User'], function() {
    Route::get('/account/events', 'Account\EventsController@index')->name('user.account.events.index');
    Route::get('/profile/{id}', 'ProfileController@index')->name('user.profile')->where('id', '[0-9]+');
});

// Events
Route::get('/events/create', 'EventsController@create')->name('events.create');
Route::post('/events', 'EventsController@postCreate')->name('events.postCreate');
Route::get('/events/{id}/edit', 'EventsController@edit')->name('events.edit')->where('id', '[0-9]+');
Route::post('/events/{id}', 'EventsController@postEdit')->name('events.postEdit')->where('id', '[0-9]+');
Route::get('/events/{id}/reschedule', 'EventsController@reschedule')->name('events.reschedule')->where('id', '[0-9]+');
Route::post('/events/{id}/reschedule', 'EventsController@postReschedule')->name('events.postReschedule')->where('id', '[0-9]+');
Route::get('/events/{id}/cancel', 'EventsController@cancel')->name('events.cancel')->where('id', '[0-9]+');
Route::get('/events/search', 'EventsController@search')->name('events.search');
Route::get('/events/{id}', 'EventsController@show')->name('events.show')->where('id', '[0-9]+');

// Home
Route::get('/', 'HomeController@index')->name('home');

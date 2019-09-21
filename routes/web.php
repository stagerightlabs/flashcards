<?php

// Authentication Routes...
Route::get('auth/google', 'Auth\GoogleLoginController@redirectToProvider')->name('auth.google');
Route::get('auth/google/callback', 'Auth\GoogleLoginController@handleProviderCallback')->name('auth.google.callback');
Route::get('auth/logout', 'Auth\LogoutController@logout')->name('logout');

// Home page
Route::get('welcome', 'WelcomeController@index')->name('welcome');

// Authenticated Routes
Route::group(['middleware' => 'auth'], function () {

    // Home
    Route::get('/', 'HomeController@index')->name('home')->middleware('domain');

    // Domains
    Route::get('domains/create', 'DomainController@create')->name('domains.create');
    Route::post('domains', 'DomainController@store')->name('domains.store');
    Route::post('domains/{ulid}', 'DomainController@update')->name('domains.update');
    Route::delete('domains/{ulid}', 'DomainController@destroy')->name('domains.destroy');
});

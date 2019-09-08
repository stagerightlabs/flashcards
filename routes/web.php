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
    Route::get('home', 'HomeController@index')->name('home')->middleware('domain');

    // Domains
    Route::get('domains/create', 'DomainController@create')->name('domains.create');
    Route::post('domains', 'DomainController@store')->name('domains.store');
    Route::post('domains/{ulid}', 'DomainController@update')->name('domains.update');
    Route::delete('domains/{ulid}', 'DomainController@destroy')->name('domains.destroy');

    // Cards
    Route::get('cards', 'CardController@index')->name('cards.index');
    Route::get('cards/create', 'CardController@create')->name('cards.create');
    Route::post('cards', 'CardController@store')->name('cards.store');
    Route::get('cards/{ulid}', 'CardController@show')->name('cards.show');
    Route::get('cards/{ulid}/edit', 'CardController@edit')->name('cards.edit');
    Route::post('cards/{ulid}', 'CardController@update')->name('cards.update');
    Route::delete('cards/{ulid}', 'CardController@destroy')->name('cards.destroy');
});

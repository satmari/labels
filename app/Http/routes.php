<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', 'WelcomeController@index');

Route::get('/', 'HomeController@index');

// CBlabels - scann BB and print CB labels
Route::get('/cblabels', 'ControllerCBlabels@searchininteos');
// Route::get('/searchinteos', 'ControllerCBlabels@searchinteos');
Route::post('/searchinteos_store', 'ControllerCBlabels@searchinteos_store');

// CBlabel - scann CB and print ONE CB label
Route::get('/cblabel', 'ControllerCBlabel@searchininteos');
// Route::get('/searchinteos', 'ControllerCBlabel@searchinteos');
Route::post('/searchinteos_store_one', 'ControllerCBlabel@searchinteos_store_one');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

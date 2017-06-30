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

// CBExtralabel
Route::get('/cbextralabels', 'ControllerCBExtralabels@searchininteos');
// Route::get('/searchinteos', 'ControllerCBExtralabels@searchinteos');
Route::post('/searchinteos_store_extra', 'ControllerCBExtralabels@searchinteos_store_extra');
Route::post('/checkbox_store', 'ControllerCBExtralabels@checkbox_store');
Route::post('/typeqty_store', 'ControllerCBExtralabels@typeqty_store');

// CBlabel - scann CB and print ONE CB label
Route::get('/cblabel', 'ControllerCBlabel@searchininteos');
// Route::get('/searchinteos', 'ControllerCBlabel@searchinteos');
Route::post('/searchinteos_store_one', 'ControllerCBlabel@searchinteos_store_one');

// Printer
Route::get('/printer', 'HomeController@printer');
Route::post('/printer_set', 'HomeController@printer_set');

// Packing list labels
Route::get('/selectinbound', 'ControllerPackingList@selectinbound');
Route::post('/selectinbound_post', 'ControllerPackingList@selectinbound_post');
Route::post('/selectbatch_post/{inbound}', 'ControllerPackingList@selectbatch_post');
Route::get('/deleteinbound/{inbound}', 'ControllerPackingList@deleteinbound');
Route::get('/deleteall', 'ControllerPackingList@deleteall');

// Pallets
Route::get('/pallets', 'ControllerPallets@index');
Route::post('/printpallests_post', 'ControllerPallets@printpallests_post');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


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
//Route::get('/cblabels', 'ControllerCBlabels@searchininteos');
// Route::get('/searchinteos', 'ControllerCBlabels@searchinteos');
//Route::post('/searchinteos_store', 'ControllerCBlabels@searchinteos_store');

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

// Bundle labals
Route::get('/bundle', 'ControllerBundle@choosebundleqty');
Route::post('/bundle_qty', 'ControllerBundle@bundle_qty');
Route::post('/searchinteos_bundle', 'ControllerBundle@searchinteos_bundle');


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

// Pad Printing
Route::get('/padprint', 'ControllerPadPrinting@index');
Route::post('/padprint_post', 'ControllerPadPrinting@padprint_post');
Route::post('/padprint_print', 'ControllerPadPrinting@padprint_print');

Route::get('padprint_conf', 'ControllerPadPrintingConf@index');
Route::get('add_new_padprint_conf', 'ControllerPadPrintingConf@add_new_padprint_conf');
Route::post('padprint_conf_insert', 'ControllerPadPrintingConf@padprint_conf_insert');
Route::get('padprint_conf/{id}', 'ControllerPadPrintingConf@padprint_conf_edit');
Route::post('padprint_conf_update/{id}', 'ControllerPadPrintingConf@padprint_conf_update');


// SAP acc
Route::get('sap_acc', 'SAP_acc@index');
Route::post('take_sap_code', 'SAP_acc@take_sap_code');

// SAP acc
Route::get('sap_hu', 'SAP_hu@index');
Route::post('take_sap_code_su', 'SAP_hu@take_sap_code_su');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


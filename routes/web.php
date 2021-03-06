<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/support', 'RedirectController@support');
Route::get('/invite', 'RedirectController@invite');
Route::get('/invite-cutting-edge', 'RedirectController@inviteCuttingEdge');
Route::get('/self-hosting', 'RedirectController@selfhosting');
Route::get('/developer-handbook', 'RedirectController@developerHandbook');

Route::get('/{any}', 'SpaController@index')->where('any', '.*');

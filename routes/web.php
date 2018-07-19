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

Route::get('/', 'ClientController@index');
Route::post('/search', 'ClientController@search');
//ROTA ABAIXA É PRA FUNCIONAR O PAGINATION
Route::get('/search', 'ClientController@search');

Route::get('/recs', 'ClientController@get_recomendations');
Route::get('/profile', 'ClientController@get_profile');
Route::get('/updates', 'ClientController@get_updates');
Route::get('/meta', 'ClientController@get_meta');
Route::get('/like', 'ClientController@like');
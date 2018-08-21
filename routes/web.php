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

Route::get('/', 'SiteController@index');

Route::get('/teste', 'SiteController@teste');

Route::get('/tinder-tools', 'TinderController@index');

//AUTENTICAÇÃO
Route::get('/tinder-tools/login', 'TinderLoginController@login');
Route::get('/tinder-tools/login/facebook', 'TinderLoginController@login_fb');
Route::post('/tinder-tools/login/facebook', 'TinderLoginController@login_fb_post');
Route::get('/tinder-tools/login/telefone', 'TinderLoginController@login_phone');
Route::post('/tinder-tools/login/telefone', 'TinderLoginController@login_phone_post');
Route::post('/tinder-tools/login/telefone/confirmar', 'TinderLoginController@confirm_login_phone');
Route::get('/tinder-tools/logout', 'TinderLoginController@logout');
//SEARCH
Route::post('/tinder-tools/search', 'TinderController@search');
//ROTA ABAIXO É PRA FUNCIONAR O PAGINATION
Route::get('/tinder-tools/search', 'TinderController@search');
Route::get('/tinder-tools/recs', 'TinderController@ajax_recomendations');
Route::get('/tinder-tools/first-access', 'TinderController@first_access');
//LIKE(S)
Route::get('/tinder-tools/like/{id}', 'TinderController@like');
Route::get('/tinder-tools/likes', 'TinderController@likes');
//SUPER(S)
Route::get('/tinder-tools/super-like/{id}', 'TinderController@super_like');
//PASS(ES)
Route::get('/tinder-tools/pass/{id}', 'TinderController@pass');


Route::get('/tinder-tools/likes-remaining', 'TinderController@likes_remaining');
Route::get('/tinder-tools/meta', 'TinderController@get_meta');//PEGAR PHONE_ID

/*
Route::get('/tinder-tools', 'TinderController@index');
Route::post('/tinder-tools/search', 'TinderController@search');
//ROTA ABAIXO É PRA FUNCIONAR O PAGINATION
Route::get('/tinder-tools/search', 'TinderController@search');
Route::get('/recs', 'ClientController@get_recomendations');
Route::get('/profile', 'TinderController@get_profile');
Route::get('/updates', 'TinderController@get_updates');//MATCHES
Route::get('/meta', 'TinderController@get_meta');//RATING->LIKES_REMAINING
Route::get('/massive-like', 'ClientController@massive_like');
Route::get('/like/{id}', 'ClientController@like');
Route::get('/teste', 'ClientController@teste');
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('cron', 'CronController@index');

Route::get('trakt', 'TraktController@lastWatchedMovie');

Route::get('movie/{movie_id}', 'TmdbController@');

Route::get('twitter', 'TwitterController@index');
Route::get('twitter/change-banner', 'TwitterController@changeBanner');

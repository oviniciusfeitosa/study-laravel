<?php

use Illuminate\Support\Facades\Route;

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


// redirecting the user to the OAuth provider
Route::get('login/{provider}', 'App\Http\Controllers\Auth\LoginController@redirectToProvider');
// receiving the callback from the provider after authentication
Route::get('login/{provider}/callback', 'App\Http\Controllers\Auth\LoginController@handleProviderCallback');

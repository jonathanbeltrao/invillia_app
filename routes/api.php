<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function() {
    # Users
    Route::get('user', 'App\Http\Controllers\Api\AuthController@user');
    Route::get('logout', 'App\Http\Controllers\Api\AuthController@logout');

    # Customers
    Route::get('customers', 'App\Http\Controllers\Api\CustomerController@all');
    Route::get('customer/{id}', 'App\Http\Controllers\Api\CustomerController@get');
});

# XML Routes (No Auth Needed)
Route::post('upload', 'App\Http\Controllers\Api\XmlController@upload');

# Create/Login User
Route::post('register', 'App\Http\Controllers\Api\AuthController@register');
Route::post('login', 'App\Http\Controllers\Api\AuthController@login')->name('login');

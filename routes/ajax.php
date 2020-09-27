<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Ajax routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "ajax" middleware group. Enjoy building your Ajax!
|
*/

Route::prefix('v1')->namespace('v1')->group(function () {
  Route::post('login', 'Login@index');

  Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', 'Logout@index');
  });
});
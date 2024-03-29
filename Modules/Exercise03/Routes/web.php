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

Route::prefix('exercise03')->name('exercise03.')->group(function () {
    Route::get('/', 'ProductController@index')->name('index');
    Route::post('/', 'ProductController@checkout')->name('checkout');
});

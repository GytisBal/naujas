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



Route::resource('users', 'UsersController');
Route::post('users/{user}/createChild', 'UsersController@createChild' )->name('users.createChild');
Route::resource('devices', 'DevicesController');
Route::get('users/{user}/devices', 'UserDeviceController@show' )->name('user.devices');
Route::post('users/{user}/addDevice', 'UserDeviceController@addDevice' )->name('user.addDevice');
Route::delete('users/devices/removeDevice', 'UserDeviceController@removeDevice' )->name('user.removeDevice');
Auth::routes();
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/', 'Auth\LoginController@login')->name('login.submit');



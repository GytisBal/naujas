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

Auth::routes();


Route::get('/', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/', 'Auth\AdminLoginController@login')->name('admin.login.submit');



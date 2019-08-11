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

Route::get('/admin', 'PagesController@admin');

Route::resource('users', 'UsersController');
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/admin', 'AdminController@index')->name('admin.dashboard');
Route::get('/', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/', 'Auth\AdminLoginController@login')->name('admin.login.submit');
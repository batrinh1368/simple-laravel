<?php

use Illuminate\Support\Facades\Artisan;
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

Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
Route::post('login', 'Auth\LoginController@login');
Route::any('logout', 'Auth\LoginController@logout')->name('admin.logout');

Route::group(['middleware' => ['admin'], 'as' => 'admin::'], function () {
    Route::get('/clear-all', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        flash()->success('Cleared!');
        return redirect(route('admin::index'));
    })->name('clear');

    Route::get('/', 'HomeController@index')->name('dashboard');

    Route::get('config', 'ConfigController@index')->name('config.index');
    Route::post('config', 'ConfigController@saveConfig')->name('config.store');

    Route::resource('users', 'UserController');
    Route::resource('blogs', 'BlogController');

});

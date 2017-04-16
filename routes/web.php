<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();
Route::group(['middleware' => ['user_language', 'auth', 'user_permission']], function () {
    Route::get('/home', 'HomeController@index')->name('home.index');
    Route::resource('role', 'RoleController', ['parameters' => ['role' => 'id'], 'except' => ['show', 'destroy']]);
    Route::get('role/{id}/permission', 'RoleController@permission')->name('role.permission');
    Route::post('role/{id}/permission', 'RoleController@setPermission')->name('role.set_permission');
    Route::resource('users', 'UserController', ['parameters' => ['users' => 'id'], 'except' => ['show']]);
    Route::get('users/profile', 'UserController@changeProfile')->name('users.profile.ignore');
    Route::put('users/profile', 'UserController@storeProfile')->name('users.store_profile.ignore');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('log.index');
    Route::get('player', 'PlayerController@index')->name('player.index');
    Route::delete('player/{id}/lock', 'PlayerController@lock')->name('player.lock');
    Route::get('master-data', 'MasterDataController@index')->name('masterdata.index');
    Route::get('json', 'JsonController@index');
    Route::post('json', 'JsonController@createJson')->name('json.create');
    Route::get('zipfile', 'JsonController@zipFile');
    Route::get('zip-index', 'JsonController@createZip');
});

Route::get('demo', 'DemoController@index');

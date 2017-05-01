<?php

use Illuminate\Http\Request;

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

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:api');
//
Route::group(['namespace' => 'Api'], function () {
    //abc song
    Route::post('receipt/verify', 'ReceiptController@verifyReceiptIOS');
    Route::post('receipt/android', 'ReceiptController@android');
    //123 numbers
    Route::post('receipt/number-ios', 'NumberController@verifyReceiptIOS');
    Route::post('receipt/number-android', 'NumberController@android');

    Route::get('index/rate', 'IndexController@showRateApp');
    Route::get('index/rate-number', 'IndexController@showRateApp');
    Route::post('download', 'DownloadController@index');
});

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Generate a new payments report,
Route::get('/tools/payments', 'PaymentController@getPayments');

// Restart values and start a new month.
Route::get('/tools/new', 'PaymentController@newMonth');

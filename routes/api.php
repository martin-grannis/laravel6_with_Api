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

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');


Route::group(['middleware' => ['guest']], function () {
    //only guests can access these routes
});


Route::group(['middleware' => 'auth.api'], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('userDetails', 'AuthController@userDetails');
    Route::resource('products', 'ProductController');
});


// this next is routes for token return type authentication
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('login', 'PassportController@login');
// Route::post('register', 'PassportController@register');
 
// Route::middleware('auth:api')->group(function () {
//     Route::get('user', 'PassportController@details');
 
//     Route::resource('products', 'ProductController');
// });
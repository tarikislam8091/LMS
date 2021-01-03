<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group(['prefix' => '/v1','middleware' => ['api']], function() {

	Route::get('getdata',function (){
        echo "Hello API 2";
    });

	Route::post('login', 'UserController@login');
	Route::post('register', 'UserController@register');
	Route::group(['middleware' => 'auth:api'], function()
	{
	   Route::post('user/details', 'UserController@UserDetails');
	   Route::post('products', 'UserController@AllProducts');
	});

	Route::get('test1', 'UserController@UserDetails');
   	Route::get('test2', 'UserController@AllProducts');

}); 


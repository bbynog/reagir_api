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

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('ocurrences', 'Api\OcurrenceController@index');
    Route::post('ocurrences', 'Api\OcurrenceController@store');
    Route::put('ocurrences/{id}', 'Api\OcurrenceController@update');
    Route::delete('ocurrences/{id}', 'Api\OcurrenceController@delete');
    Route::get('ocurrences/{id}', 'Api\OcurrenceController@show');
});

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login')->name('login');


#TODO: Não seria interessante proteger nossos cruds para que apenas usuários permitidos(logados) possam utiliza-los?
Route::get('ocurrence_types', 'Api\OcurrenceTypeController@index');
Route::post('ocurrence_types', 'Api\OcurrenceTypeController@store');
Route::put('ocurrence_types/{id}', 'Api\OcurrenceTypeController@update');
Route::delete('ocurrence_types/{id}', 'Api\OcurrenceTypeController@delete');
Route::get('ocurrence_types/{id}', 'Api\OcurrenceTypeController@show');
Route::put('ocurrence_types/status/{id}', 'Api\OcurrenceTypeController@status');

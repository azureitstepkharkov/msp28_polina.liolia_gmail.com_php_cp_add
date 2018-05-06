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
//Route::post('user_account/{id}/avatar', 'UserAccountController@updateAvatar')->name('userAccount.updateAvatar');

Route::get('users', 'UserController@indexApi');
Route::get('technologies', 'TechnologyController@indexApi');
Route::get('tasks', 'TaskController@indexApi');
Route::get('projects', 'ProjectController@indexApi');
Route::get('data', 'HomeController@getJsonData');
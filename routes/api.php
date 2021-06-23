<?php

use App\Events\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::namespace('Api\Auth')->group(function () {
    // User Auth
    Route::post('user/register', 'UserController@register');
    Route::post('user/login', 'UserController@login');

    // Admin Auth
    Route::post('admin/login', 'AdminController@login');

    // Author Auth
    Route::post('author/login', 'AuthorController@login');
});

// User Auth
Route::namespace('Api\Auth')->middleware('auth:user')->group(function () {
    Route::get('user/logout', 'UserController@logout');
});

// Admin Auth
Route::namespace('Api')->middleware('auth:admin')->group(function () {
    Route::get('admin/logout', 'Auth\AdminController@logout');
    Route::apiResource('admins', 'AdminController');
    Route::apiResource('authers', 'AuthorController');
});

// Author Auth
Route::namespace('Api')->middleware('auth:author')->group(function () {
    Route::get('author/logout', 'Auth\AuthorController@logout');
});

// Chat Message For User
Route::namespace('Api')->middleware('auth:user')->group(function () {
    Route::post('message/send', 'ChatController@sendMessage');
    Route::post('message/{id}', 'ChatController@getMessagesAuthToUser');
    Route::post('read/message/{id}', 'ChatController@readMessage');
    Route::post('read/messages/{id}', 'ChatController@readMessages');
    Route::get('messages', 'ChatController@getMessagesAuth');
});

// Room Message Fro User
Route::namespace('Api')->middleware('auth:user')->group(function () {
    Route::post('room/store', 'RoomController@addNewRoom');
    Route::get('room/index', 'RoomController@getAllRooms');
    Route::get('room/myroom', 'RoomController@getMyRoom');
    Route::delete('room/delete/{id}', 'RoomController@deleteRoom');
});


// Route::post('sender', function (Request $request) {

//     $userAuth = Auth::user();
//     broadcast(new Chat($request->get('message'), $userAuth))->toOthers();

//     return ['success'];
// })->middleware('auth:admin');

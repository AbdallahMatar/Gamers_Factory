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
    // Route::apiResource('admins', 'AdminController');
    // Route::apiResource('authers', 'AuthorController');
});

// Author Auth
Route::namespace('Api')->middleware('auth:author')->group(function () {
    Route::get('author/logout', 'Auth\AuthorController@logout');
});

// Admin And Author Auth
// Route::namespace('Api')->middleware('auth:admin,author')->group(function () {
//     Route::apiResource('categories', 'CategoryController');
//     Route::apiResource('articles', 'ArticleController');
// });

// Friend System
Route::namespace('Api')->middleware('auth:user')->group(function () {
    Route::get('my/friend', 'FriendController@getAllFriend');
    Route::post('add/friend/{id}', 'FriendController@addFriend');
    Route::get('pending/friend', 'FriendController@pendingFriend');
    Route::post('accepted/friend/{id}', 'FriendController@acceptFriend');
    Route::delete('remove/friend/{id}', 'FriendController@removeFriend');
});

// Chat Message For User
Route::namespace('Api')->middleware('auth:user')->group(function () {
    Route::post('message/send', 'ChatController@sendMessage');
    Route::post('message/{id}', 'ChatController@getMessagesAuthToUser');
    Route::post('read/message/{id}', 'ChatController@readMessage');
    Route::post('read/messages/{id}', 'ChatController@readMessages');
    Route::get('messages', 'ChatController@getMessagesAuth');
});

Route::namespace('Api')->middleware('auth:admin')->group(function () {
    Route::post('room/store', 'RoomController@addNewRoom');
    Route::get('room/myroom', 'RoomController@getMyRoom');
    Route::delete('room/delete/{id}', 'RoomController@deleteRoom');
});

Route::get('room/index', 'Api\RoomController@getAllRooms')->middleware('auth:admin,user');

// Room Message Fro User
Route::namespace('Api')->middleware('auth:user')->group(function () {
    Route::post('store/message', 'ConversationController@storeMessage');
    Route::get('index/category', 'CategoryController@index');
});


Route::get('@me', 'ControllerHelper@getUserData')->middleware('auth:user');

Route::get('index/article', 'Api\ArticleController@index')->middleware('auth:user');

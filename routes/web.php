<?php

use App\Events\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});


// Route::post('sender', function (Request $request) {
//     $userAuth = Auth::user();
//     broadcast(new Chat($request->get('message'), $userAuth))->toOthers();

//     return ['success'];
// });

Route::prefix('cms/admin')->namespace('Cms\Auth')->group(function () {
    Route::get('/login', 'AdminAuthController@showLoginView')->name('admin.login_view');
    Route::post('/login', 'AdminAuthController@login')->name('admin.login');
});
//Admin Authenticated

Route::prefix('cms/admin')->middleware('auth:admin_web')->group(function () {
    Route::view('/', 'admin.dashboard')->name('admin.dashboard');
    Route::get('/logout', 'Cms\Auth\AdminAuthController@logout')->name('admin.logout');
});
//Admin Authenticated

Route::prefix('cms/admin')->namespace('Cms')->middleware('auth:admin_web')->group(function () {
    Route::resource('admins', 'AdminController');
    Route::resource('authors', 'AuthorController');
    Route::resource('categories', 'CategoryController');
    Route::resource('articles', 'ArticleController');
    // Route::resource('states', 'StateController');
    // Route::resource('employees', 'EmployeeController');
    // Route::resource('projects', 'ProjectController');
});

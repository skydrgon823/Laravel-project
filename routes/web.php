<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
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
    return view('auth.login');
});

Route::get('/logout', function () {
    return view('auth.login');
});

Route::post('/signup', [App\Http\Controllers\AuthController::class, 'create'])->name('signup');
Route::post('/signin', [App\Http\Controllers\AuthController::class, 'authenticate'])->name('signin');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
		// profile
	Route::get('/view-profile', [AdminController::class,'view_profile'])->name('view-profile');
	Route::post('/update-profile', [AdminController::class,'update_profile'])->name('update-profile');
	// password
	Route::get('/view-password', [AdminController::class,'view_password'])->name('view-password');
	Route::post('/update-password', [AdminController::class,'update_password'])->name('update-password');
	
	// Employee
	Route::get('/employee', [AdminController::class,'employee'])->name('employee');
	Route::post('/store-employee', [AdminController::class,'store_employee'])->name('store-employee');
	Route::post('/update-employee', [AdminController::class,'update_employee'])->name('update-employee');
	Route::post('/delete-employee', [AdminController::class,'delete_employee'])->name('delete-employee');
	Route::post('/employee', [AdminController::class,'search_employee'])->name('search-employee');
	
	// Category
	Route::get('/category', [AdminController::class,'category'])->name('category');
	Route::post('/store-category', [AdminController::class,'store_category'])->name('store-category');
	Route::post('/update-category', [AdminController::class,'update_category'])->name('update-category');
	Route::post('/delete-category', [AdminController::class,'delete_category'])->name('delete-category');
	
	// jobchat
	Route::get('/jobchat', [AdminController::class,'jobchat'])->name('jobchat');
	Route::get('/message-list/{id}/{lang}', [AdminController::class,'message_list'])->name('message-list');
	Route::post('/store-message-header', [AdminController::class,'store_message_header'])->name('store-message-header');
	Route::post('/store-message', [AdminController::class,'store_message'])->name('store-message');
	
	// generation usercode
	Route::get('/generation-usercode', [AdminController::class,'generation_usercode'])->name('generation-usercode');
	Route::post('/generate-usercode', [AdminController::class,'generate_usercode'])->name('generate-usercode');
	Route::post('/delete-freecode', [AdminController::class,'delete_freecode'])->name('delete-freecode');
	Route::post('/search-usercode', [AdminController::class,'search_usercode'])->name('search-usercode');

	// notification
	Route::get('/push-notification', [App\Http\Controllers\AdminController::class, 'view_push_notification'])->name('push-notification');
	Route::post('/save-token', [App\Http\Controllers\AdminController::class, 'saveToken'])->name('save-token');
	Route::post('/send-notification', [App\Http\Controllers\AdminController::class, 'sendNotification'])->name('send.notification');
	
	// Language
	Route::get('/message-list/3/changeLanguage', [App\Http\Controllers\AdminController::class, 'changeLanguage'])->name('changeLanguage');

	// download
	Route::get('/downloadfile/{id}', [App\Http\Controllers\AdminController::class, 'downloadfile'])->name('downloadfile');

}
);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

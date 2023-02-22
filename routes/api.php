<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

	// Route::prefix('/v1')->group(function () {
	Route::group([
		'middleware' => ['cors'],
		'prefix' => '/v1',
	], function() {
		// Route::get('/dashboard', [ApiController::class,'index'])->name('dashboard');
		// Route::get('/employee', [ApiController::class,'employee'])->name('employee');
		// Route::post('/store-employee', [ApiController::class,'store_employee'])->name('store-employee');
		// Route::post('/update-employee', [ApiController::class,'update_employee'])->name('update-employee');
		// Route::post('/delete-employee', [ApiController::class,'delete_employee'])->name('delete-employee');

		// Route::get('/category', [ApiController::class,'category'])->name('category');
		// Route::post('/store-category', [ApiController::class,'store_category'])->name('store-category');
		// Route::post('/update-category', [ApiController::class,'update_category'])->name('update-category');
		// Route::post('/delete-category', [ApiController::class,'delete_category'])->name('delete-category');		
		
		// Route::post('/jobchat', [ApiController::class,'jobchat'])->name('jobchat');
		// Route::post('/joballchat', [ApiController::class,'joballchat'])->name('joballchat');
		// Route::post('/message_list', [ApiController::class,'message_list'])->name('message_list');
		// Route::post('/signattachment', [ApiController::class,'signattachment'])->name('signattachment');
		// Route::post('/send_newmsg', [ApiController::class,'send_newmsg'])->name('send_newmsg');
		// Route::post('/store_message', [ApiController::class,'store_message'])->name('store_message');

		// Route::post('/send_sms', [ApiController::class,'send_sms'])->name('send_sms');
		
		// Route::get('/dashboard', [ApiController::class,'index']);
		Route::get('/employee', [ApiController::class,'employee']);
		Route::post('/store-employee', [ApiController::class,'store_employee']);
		Route::post('/update-employee', [ApiController::class,'update_employee']);
		Route::post('/delete-employee', [ApiController::class,'delete_employee']);

		Route::get('/category', [ApiController::class,'category']);
		Route::post('/store-category', [ApiController::class,'store_category']);
		Route::post('/update-category', [ApiController::class,'update_category']);
		Route::post('/delete-category', [ApiController::class,'delete_category']);		
		
		Route::post('/dashboard', [ApiController::class,'dashboard']);
		Route::post('/joballchat', [ApiController::class,'joballchat']);
		Route::post('/jobdrawer', [ApiController::class,'jobdrawer']);
		Route::post('/jobwall', [ApiController::class,'jobwall']);
		Route::post('/attachment_list', [ApiController::class,'attachment_list']);
		
		
		Route::post('/jobchat', [ApiController::class,'jobchat']);
		Route::post('/message_list', [ApiController::class,'message_list']);
		Route::post('/send_code_signattachment', [ApiController::class,'send_code_signattachment']);
		Route::post('/verify_code_signattachment', [ApiController::class,'verify_code_signattachment']);
		
		Route::post('/downloadfile', [ApiController::class,'downloadfile']);


		Route::post('/signattachment', [ApiController::class,'signattachment']);
		Route::post('/send_newmsg', [ApiController::class,'send_newmsg']);
		Route::post('/store_message', [ApiController::class,'store_message']);
		Route::post('/send_regccode', [ApiController::class,'send_regccode']);
		Route::post('/send_regsms', [ApiController::class,'send_regsms']);
		Route::post('/send_regcode', [ApiController::class,'send_regcode']);

		Route::post('/send_loginsms', [ApiController::class,'send_loginsms']);
		Route::post('/send_logincode', [ApiController::class,'send_logincode']);

		Route::post('/save_token', [ApiController::class, 'save_token']);
		Route::post('/send_notification', [ApiController::class, 'send_notification']);

		Route::post('/send_notification_testing', [ApiController::class,'send_notification_testing']);
	});


<?php

use Illuminate\Support\Facades\Route;
use Modules\Feedback\Http\Controllers\ViberController;

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

Route::group([], function () {
    // Route::resource('feedback', FeedbackController::class)->names('feedback');
});

Route::get('feedback/viber/register-webhook', [ViberController::class, 'registerWebhook'])->name('viber.registerWebhook');

// https://4801-2405-9800-bca0-c927-b880-a886-517a-7420.ngrok-free.app/

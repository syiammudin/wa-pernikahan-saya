<?php

use App\Http\Controllers\PhoneBoookController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\WhatsAppController;
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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('layouts.app');
    });
    Route::resource('phoneBook', PhoneBoookController::class);
    Route::resource('templateMessage', TemplateController::class);
    Route::post('sendWhatsapp', [WhatsAppController::class, 'sendWa']);
    Route::get('connect', [WhatsAppController::class, 'connect']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

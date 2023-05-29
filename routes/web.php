<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payment/intermediate', [\App\Http\Controllers\PaymentController::class, 'intermediate'])->name('payment.intermediate');
Route::post('payment3D',[\App\Http\Controllers\PaymentController::class,'processPayment3d'])->name('payment.payment-3d');
Route::post('payment2D',[\App\Http\Controllers\PaymentController::class,'processPayment2d'])->name('payment.payment-2d');
Route::get('/payment/main', [\App\Http\Controllers\PaymentController::class, 'mainPage'])->name('payment.main');
Route::get('/payment/get-token', [\App\Http\Controllers\PaymentController::class, 'getToken'])->name('payment.get-token');

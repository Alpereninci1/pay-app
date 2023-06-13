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

Route::post('get-pos',[\App\Http\Controllers\Payment\PaymentController::class,'getPos'])->name('payment.get-pos');
Route::get('index',[\App\Http\Controllers\Payment\PaymentController::class,'index'])->name('payment.index');
Route::post('payment',[\App\Http\Controllers\Payment\PaymentController::class,'processPayment'])->name('payment');
Route::get('get-token',[\App\Http\Controllers\Payment\PaymentController::class,'getToken'])->name('get-token');
Route::get('success',[\App\Http\Controllers\Payment\PaymentController::class,'success'])->name('success');
Route::get('error',[\App\Http\Controllers\Payment\PaymentController::class,'error'])->name('error');

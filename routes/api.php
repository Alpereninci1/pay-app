<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['api'])->group(function () {
    // Rotalar buraya eklenecek
    Route::get('get-token',[\App\Http\Controllers\Payment\PaymentController::class,'getToken']);
    Route::post('payment3D',[\App\Http\Controllers\Payment\PaymentController::class,'processPayment3d']);
    Route::post('payment2D',[\App\Http\Controllers\Payment\PaymentController::class,'processPayment2d']);
    Route::get('get-installments',[\App\Http\Controllers\Payment\PaymentController::class,'getInstallment']);
    Route::post('get-pos',[\App\Http\Controllers\Payment\PaymentController::class,'getPos']);
    Route::post('payByCardTokenNonSecure',[\App\Http\Controllers\Payment\PaymentController::class,'payByCardTokenNonSecure']);
});



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get-token',[\App\Http\Controllers\PaymentController::class,'getToken']);
Route::post('payment3D',[\App\Http\Controllers\PaymentController::class,'processPayment3d']);
Route::post('payment2D',[\App\Http\Controllers\PaymentController::class,'processPayment2d']);
Route::get('get-installments',[\App\Http\Controllers\PaymentController::class,'getInstallment']);
Route::post('get-pos',[\App\Http\Controllers\PaymentController::class,'getPos']);
Route::post('payByCardTokenNonSecure',[\App\Http\Controllers\PaymentController::class,'payByCardTokenNonSecure']);

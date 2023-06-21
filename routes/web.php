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

// TODO: Tüm route tanımlamalarında kullanılan controller ların namespace i olan \App\Http\Controllers\Payment\ yolunu use kısmında tanımlayabliriz
Route::post('get-pos',[\App\Http\Controllers\Payment\PaymentController::class,'getPos'])->name('payment.get-pos');
// TODO: anasayfa açıldığında url de index olmasın. base url ile anasayfa açılmalı
Route::get('index',[\App\Http\Controllers\Payment\PaymentController::class,'index'])->name('payment.index');
Route::post('payment',[\App\Http\Controllers\Payment\PaymentController::class,'processPayment'])->name('payment');
Route::get('get-token',[\App\Http\Controllers\Payment\PaymentController::class,'getToken'])->name('get-token');
Route::get('success',[\App\Http\Controllers\Payment\PaymentController::class,'success'])->name('success');
Route::get('error',[\App\Http\Controllers\Payment\PaymentController::class,'error'])->name('error');
Route::get('get-installments',[\App\Http\Controllers\Payment\PaymentController::class,'getInstallment']);


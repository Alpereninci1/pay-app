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
Route::get('payment3D-view',[\App\Http\Controllers\PaymentController::class,'payment3DView'])->name('payment.payment-3d-view');
Route::post('payment2D',[\App\Http\Controllers\PaymentController::class,'processPayment2d'])->name('payment.payment-2d');
Route::get('payment2D-view',[\App\Http\Controllers\PaymentController::class,'payment2DView'])->name('payment.payment-2d-view');
Route::get('/payment/main', [\App\Http\Controllers\PaymentController::class, 'mainPage'])->name('payment.main');
Route::get('/payment/get-token', [\App\Http\Controllers\PaymentController::class, 'getToken'])->name('payment.get-token');
Route::get('/payment/get-installment', [\App\Http\Controllers\PaymentController::class, 'getInstallment'])->name('payment.get-installment');
Route::post('get-pos',[\App\Http\Controllers\PaymentController::class,'getPos'])->name('payment.get-pos');
Route::get('get-pos-view',[\App\Http\Controllers\PaymentController::class,'getPosView'])->name('payment.get-pos-view');
Route::post('paymentCardToken',[\App\Http\Controllers\PaymentController::class,'payByCardTokenNonSecure'])->name('payment.pay-by-card-token');
Route::get('paymentCardToken-view',[\App\Http\Controllers\PaymentController::class,'payByCardTokenView'])->name('payment.pay-by-card-token-view');
Route::get('index',[\App\Http\Controllers\PaymentController::class,'index'])->name('payment.index');
Route::post('payment',[\App\Http\Controllers\PaymentController::class,'processPayment'])->name('payment');
Route::get('get-token',[\App\Http\Controllers\PaymentController::class,'getToken'])->name('get-token');
Route::get('success',function (){
   return view('success');
});
Route::get('error',function (){
    return view('error');
});

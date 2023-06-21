<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use \App\Http\Controllers\Payment\PaymentController;

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

Route::post('get-pos',[PaymentController::class,'getPos'])->name('payment.get-pos');
Route::get('',[PaymentController::class,'index'])->name('payment.index');
Route::post('payment',[PaymentController::class,'processPayment'])->name('payment');
Route::get('get-token',[PaymentController::class,'getToken'])->name('get-token');
Route::get('success',[PaymentController::class,'success'])->name('success');
Route::get('error',[PaymentController::class,'error'])->name('error');
Route::get('get-installments',[PaymentController::class,'getInstallment']);


Route::get('send-mail', function () {

    $details = [
        'title' => 'Mail from Alperen',
        'body' => 'This is for testing email using smtp'
    ];

    Mail::to('alperen25inci@gmail.com')->send(new \App\Mail\MyTestMail($details));

    dd("Email is Sent.");
});

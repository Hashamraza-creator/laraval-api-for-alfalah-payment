<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
Route::get('/pay', [PaymentController::class, 'pay'])->name('alfalah.pay');
Route::get('/callback', [PaymentController::class, 'callback'])->name('alfalah.callback');
Route::get('/', function () {
    return view('welcome');
});

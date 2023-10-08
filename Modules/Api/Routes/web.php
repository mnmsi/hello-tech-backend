<?php

use App\Http\Controllers\AmarPayController;
use Modules\Api\Http\Controllers\Payment\PaymentController;

//Route::prefix("payment")->as("payment")->controller(PaymentController::class)->group(function () {
//    Route::get('test', 'test');
//    Route::post('success', 'success');
//    Route::post('failure', 'failure');
//    Route::post('cancel', 'cancel');
//    Route::post('ipn', 'ipn');
//});

Route::get('payment',[AmarPayController::class,'payment'])->name('payment');

//You need declear your success & fail route in "app\Middleware\VerifyCsrfToken.php"
Route::post('success',[AmarPayController::class,'success'])->name('success');
Route::post('fail',[AmarPayController::class,'fail'])->name('fail');
Route::get('cancel',[AmarPayController::class,'cancel'])->name('cancel');

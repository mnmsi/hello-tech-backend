<?php

use App\Http\Controllers\Nova\LoginController;
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
    return redirect()->route('login.page');
});

Route::get('/admin-login',[LoginController::class,'loginPage'])->name('login.page');
Route::post('login-check', [LoginController::class, 'loginCheck'])->name('admin-login-check');



<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::post('/register', [AuthController::class, 'register'])->name(name: 'register');
Route::get('/register', [AuthController::class, 'gotoRegister'])->name(name: 'register_form');
Route::post('/login', [AuthController::class, 'login'])-> name('login');
Route::get('/login', [AuthController::class, 'gotoLogin'])->name('login_form');

Route::get('/', [AuthController::class, 'homepage'])->name('home');

Route::middleware(['auth.session'])->group(function () {  
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', [AuthController::class, 'userInfo']);
});
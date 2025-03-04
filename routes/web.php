<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
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



Route::post('/register', [AuthController::class, 'registerAccount'])->name(name: 'registerAccount');
Route::get('/register', [AuthController::class, 'gotoRegister'])->name(name: 'register_form');

Route::post('/login', [AuthController::class, 'login'])
-> name('login')->middleware(['single.session']);

Route::get('/login', [AuthController::class, 'gotoLogin'])->name('login_form');

Route::get('/', [AuthController::class, 'homepage'])->name('home')-> middleware("single.session",'check.role:admin');

Route::middleware(['auth.session'])->group(function () {  
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', [AuthController::class, 'userInfo']);
    Route::get('/search/product', [ProductController::class,'index'])->name('search_form');
    Route::get('/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('/update/{id}', [ProductController::class, 'update'])->name('products.update');
   
});
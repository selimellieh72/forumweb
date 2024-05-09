<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;


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
    return view('index');
})->name('home');

Route::get('/login', function () {
    return view('login');

})->name('login')->middleware('guest');

Route::post('/login',  [LoginController::class, 'login']);

Route::post('/logout',  [LoginController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return view('sign-up');
})->name('register')->middleware('guest');

Route::post('/register',  [RegisterController::class, 'register']);

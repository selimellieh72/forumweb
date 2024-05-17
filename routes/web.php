<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

use App\Models\Post;


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

Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('/login', [
    LoginController::class, 'index'
])->middleware('guest');

Route::post('/login',  [LoginController::class, 'login'])->name('login');

Route::post('/logout',  [LoginController::class, 'logout'])->name('logout');

Route::get('/register',
[
    RegisterController::class, 'index'
]
)->name('register')->middleware('guest');

Route::post('/register',  [RegisterController::class, 'register'])
    ->middleware('guest');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile')
    ->middleware('auth');

Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')
    ->middleware('auth');


Route::get('/posts/{post}',  [PostController::class, 'show'])->name('posts.show');

Route::post('/posts',  [PostController::class, 'store'])->name('posts.store')
    ->middleware('auth')->middleware('active')
;

Route::post('/posts/{post}', [ReplyController::class, 'store'])->name('posts.reply')
->middleware('auth')->middleware('active')

    ;
Route::delete('/replies/{reply}',  [ReplyController::class, 'destroy'])->name('replies.destroy')
    ->middleware('auth')->middleware('active');
Route::delete('/posts/{post}',  [PostController::class, 'destroy'])->name('posts.destroy')
    ->middleware('auth')->middleware('active');

Route::patch('/posts/{post}',  [PostController::class, 'update'])->name('posts.update')
    ->middleware('auth')->middleware('active');

Route::post('/posts/{post}/like',  [PostController::class, 'like'])->name('posts.like')
    ->middleware('auth')->middleware('active');

Route::get('/admin',  [AdminController::class, 'index'])->name('admin')
    ->middleware('admin');

Route::post('/admin/{user}/deactivate',  [AdminController::class, 'toogleActivate'])
    ->middleware('admin')->name('toogleActivate');








    
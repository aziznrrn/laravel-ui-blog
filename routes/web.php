<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return redirect()->route('posts.index');
});
Route::get('/posts', [App\Http\Controllers\PostController::class, 'index'])->name('posts.index');
Route::get('posts/{post}', [App\Http\Controllers\PostController::class, 'show'])->name('posts.show');

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('articles', App\Http\Controllers\ArticleController::class)->except(['create', 'show']);
    Route::get('categories/list', [App\Http\Controllers\CategoryController::class, 'list'])->name('categories.list');
    Route::resource('categories', App\Http\Controllers\CategoryController::class)->except(['create', 'show']);
});
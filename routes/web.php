<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

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

Route::get('/admin-panel', function () {
    return view('home');
});

Route::get('/admin-panel/articles/create', [ArticleController::class, 'create']);
Route::get('/admin-panel/articles/edit/{id}', [ArticleController::class, 'edit'])->name('edit');
Route::get('/admin-panel/articles/search', [ArticleController::class, 'loadSearchArticlesView']);


Route::get('/login', function () {
    return view('login');
});

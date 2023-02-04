<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/articles/save/{id}', [ArticleController::class, 'save']);
Route::post('/articles/unpublish/{id}', [ArticleController::class, 'unpublish']);
//Route::post('/articles/publish/{id}', [ArticleController::class, 'publish']);
//Route::post('/articles/create', [ArticleController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('/articles', [ArticleController::class, 'saveChanges']);
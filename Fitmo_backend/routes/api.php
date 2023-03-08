<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\RatingsController;

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



Route::get('/categories', [CategoriesController::class, 'index']);
Route::post('/makeMap', [CategoriesController::class, 'makeMapTable']);
Route::get('/category/{categoryName}', [CategoriesController::class, 'getCategory']);

Route::get('/ratings', [RatingsController::class, 'index']);

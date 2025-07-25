<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeliveryTypeController;
use App\Http\Controllers\FonkoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

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


Route::get('/categories/remap', [CategoryController::class, 'remap']);

Route::post('/categories', [CategoryController::class, 'index']);
Route::get("/categories/maxId", [CategoryController::class, "getMaxId"]);
Route::post("/categories/update", [CategoryController::class, "updateCategories"]);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/soloProducts', [ProductController::class, 'getAllProducts']);
Route::get('/mainProducts', [ProductController::class, 'getMainProducts']);

Route::get('/productsByCategories', [ProductController::class, 'getProductsForOrders']);

Route::get('/productsByIds/{ids}', [ProductController::class, 'getProductByIds']);


Route::get('/mainCategories', [CategoryController::class, 'getMainCategories']);
Route::get('/subCategory/{id}', [CategoryController::class, 'getSubCategory']);
Route::post('/makeMap', [CategoryController::class, 'makeMapTable']);
Route::get('/category/{categoryName}', [CategoryController::class, 'getCategory']);
Route::post('/categoryProducts/{categoryName}', [ProductController::class, 'getCategoryProducts']);
Route::get('/initFilters/{categoryName}', [CategoryController::class, 'getInitFiltersFromCategory']);


Route::post('/updateOrderOfProducts', [ProductController::class, 'updateOrderOfProducts']);

Route::get('/search/{query}', [ProductController::class, 'getSearchedItems']);

Route::get('/ratings', [RatingController::class, 'index']);

Route::get('/paymentTypes', [PaymentTypeController::class, 'index']);

Route::get('/deliveryTypes', [DeliveryTypeController::class, 'index']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('/category')->group(function () {
    Route::post('/store', [CategoryController::class, 'store']);
    Route::delete('/{id}', [CategoryController::class, 'delete']);
    Route::post('/{id}', [CategoryController::class, 'update']);
});
Route::prefix('/product')->group(function () {
    Route::post('/store', [ProductController::class, 'store']);
    Route::delete('/{id}', [ProductController::class, 'delete']);
    Route::post('/{id}', [ProductController::class, 'update']);
    Route::put('/{id}/hide', [ProductController::class, 'hideProduct']);
    Route::post('/{id}/template/store', [ProductController::class, 'storeTemplate']);
    Route::get('{id}/getTemplates/', [ProductController::class, 'getTemplates']);
    Route::delete('{id}/template/{templateId}', [ProductController::class, 'deleteTemplate']);

    Route::get('/{product_url_name}', [ProductController::class, 'getSingleProduct']);
});

Route::get('/orders', [OrderController::class, 'index']);

Route::prefix('/order')->group(function () {
    Route::post('/store', [OrderController::class, 'store']);
    Route::get('/{id}', [OrderController::class, 'getOrderById']);
    Route::post('/updateStates', [OrderController::class, 'updateState']);
});


Route::get('/fonko', [FonkoController::class, 'index']);
Route::put('/fonko/{id}', [FonkoController::class, 'update']);

Route::get('/order/{hash}', [OrderController::class, 'getOrderById']);

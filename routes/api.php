<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PackagingController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TakesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{id}', [ItemController::class, 'show']);


Route::apiResource('categories', CategoryController::class);
// Route::apiResource('items', ItemController::class);
Route::apiResource('packagings', PackagingController::class);
// Route::apiResource('orders', OrderController::class);

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);

Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders/create', [OrderController::class, 'createOrder']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::put('/order-update/{id}', [OrderController::class, 'update']);
Route::delete('/order-delete/{id}', [OrderController::class, 'destroy']);



Route::get('/takes', [TakesController::class, 'index']);
Route::delete('/deleteItem-takes/{id}', [TakesController::class, 'deleteitemfromTakes']);





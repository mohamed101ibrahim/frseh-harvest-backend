<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Auth::routes();




Route::post('password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
// Route::post('/forgot-password',[AuthController::class,'sendResetLinkEmail']);
// Route::post('/resetPassword',[AuthController::class,'resetPassword']);

Route::post('/logout',[AuthController::class,'logout']);


// Route::middleware(['auth:sanctum'])->post('logout',[AuthController::class,'logout']);
Route::middleware( ['auth:sanctum'])->group(function () {
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{id}', [ItemController::class, 'show']);

Route::post('/reset',[AuthController::class,'reset']);
Route::post('/forget',[AuthController::class,'forget']);
Route::apiResource('categories', CategoryController::class);
// Route::apiResource('items', ItemController::class);
Route::apiResource('packagings', PackagingController::class);
// Route::apiResource('orders', OrderController::class);
Route::get('/orders', [OrderController::class, 'index']);

Route::get('/orders-Details', [OrderController::class, 'getAllOrderDetails']);

Route::post('/orders/create', [OrderController::class, 'createOrder']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::put('/order-update/{id}', [OrderController::class, 'update']);
Route::delete('/order-delete/{id}', [OrderController::class, 'destroy']);



Route::get('/takes', [TakesController::class, 'index']);
Route::delete('/deleteItem-takes/{id}', [TakesController::class, 'deleteitemfromTakes']);
}); 
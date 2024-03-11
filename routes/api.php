<?php

use App\Http\Controllers\CategoriesController; 
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController; 
use App\Http\Controllers\ContactController; 
use App\Http\Controllers\UserRegController; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get("/category", [CategoriesController::class, 'list']); 
Route::post("/category", [CategoriesController::class, 'create']); 
//Route::put("/category", [CategoriesController::class, 'update']); 
Route::delete("/category/{id}", [CategoriesController::class, 'delete']); 
Route::get('api/category/image/{imageName}', [CategoriesController::class, 'getImage']);


Route::get("/product", [ProductsController::class, 'create_list']); 
Route::post("/product", [ProductsController::class, 'create']); 
Route::delete("/product/{id}", [ProductsController::class, 'delete']); 
Route::get('api/product/image/{imageName}', [ProductsController::class, 'getImage']);

Route::get("/product/{category_id}", [ProductsController::class, 'productsByCategory']); 

Route::post('/product/cart', [ProductsController::class, 'addToCart']);
Route::get('/product/cart', [ProductsController::class, 'getCart']);

Route::get('/contacts', [ContactController::class, 'index']);

Route::post('/orders', [OrderController::class, 'addItem']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::get('/all-orders', [OrderController::class, 'getAllOrders']);
Route::delete('/delete-order/{id}', [OrderController::class, 'deleteOrder']);

//Route::get('/orders', [OrderController::class, 'addItem']);
//Route::post('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

Route::get('/registration', [UserRegController::class, 'showRegistrationForm']);
Route::post('/registration', [UserRegController::class, 'register']);





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



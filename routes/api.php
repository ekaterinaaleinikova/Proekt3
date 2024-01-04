<?php

use App\Http\Controllers\CategoriesController; 
use App\Http\Controllers\ProductsController; 
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
Route::put("/category", [CategoriesController::class, 'update']); 
Route::delete("/category/{id}", [CategoriesController::class, 'delete']); 

Route::get("/products", [ProductsController::class, 'create_list']); 
Route::post("/products", [ProductsController::class, 'create']); 
Route::delete("/products/{id}", [ProductsController::class, 'delete']); 


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

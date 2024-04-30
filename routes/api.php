<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["prefix" => "/v1"], function(){
    Route::group(["prefix" => "/auth"], function(){
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/logout', [AuthController::class, 'logout'])->middleware(["auth:sanctum"]);
    });


    Route::group(["middleware" => "auth:sanctum"], function(){
        Route::apiResource('/division', DivisionController::class);
        Route::apiResource('/employee', EmployeeController::class);
        Route::apiResource('/office', OfficeController::class);
        Route::apiResource('/manager', ManagerController::class);
        Route::apiResource('/product', ProductController::class);
        Route::apiResource('/category', CategoryController::class);
    });
});




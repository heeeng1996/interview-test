<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'forgotPassword']);
Route::post('/verify-email', [App\Http\Controllers\AuthController::class, 'verifyEmail']);
Route::post('/resend-verification-email', [App\Http\Controllers\AuthController::class, 'resendVerificationEmail']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
Route::put('/update-profile', [App\Http\Controllers\AuthController::class, 'update']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class)->parameter('products', 'uuid');
    Route::apiResource('categories', CategoryController::class)->parameter('categories', 'uuid');
    Route::apiResource('suppliers', SupplierController::class)->parameter('suppliers', 'uuid');
});
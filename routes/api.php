<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Domains\Auth\Controllers\Api\AuthController;

Route::prefix('v1/auth')->middleware('throttle:auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware(['auth:sanctum', 'identify-tenant'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// Route::middleware(['auth:sanctum', 'identify-tenant', 'subscribed', 'throttle:api'])->group(function () {
//     Route::post('devices', [\App\Domains\Inventory\Controllers\Api\DeviceController::class, 'store'])->middleware('limit.devices');
//     Route::apiResource('devices', \App\Domains\Inventory\Controllers\Api\DeviceController::class)->except(['store']);
    
//     Route::get('devices/{device}/active-session', [\App\Http\Controllers\Api\DeviceController::class, 'activeSession']);
    
//     Route::post('devices/{device}/start', [\App\Domains\Sessions\Controllers\Api\SessionController::class, 'start']);
//     Route::post('devices/{device}/stop', [\App\Domains\Sessions\Controllers\Api\SessionController::class, 'stop']);

//     // Reporting APIs
//     Route::get('reports/financial-overview', [\App\Domains\Reports\Controllers\Api\ReportController::class, 'financialOverview']);
//     Route::get('reports/leaderboard', [\App\Domains\Reports\Controllers\Api\ReportController::class, 'leaderboard']);
//     // POS APIs
//     Route::apiResource('products', \App\Domains\POS\Controllers\Api\ProductController::class);
//     Route::post('orders', [\App\Domains\POS\Controllers\Api\OrderController::class, 'store']);
//     Route::get('orders/{order}', [\App\Domains\POS\Controllers\Api\OrderController::class, 'show']);
//     Route::post('orders/{order}/pay', [\App\Domains\POS\Controllers\Api\OrderController::class, 'pay']);

//     // Finance APIs
//     Route::apiResource('expenses', \App\Domains\Finance\Controllers\Api\ExpenseController::class)->only(['index', 'store']);

//     // AI Insights
//     Route::get('insights', [\App\Domains\AI\Controllers\AIController::class, 'index']);
// });

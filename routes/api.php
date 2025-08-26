<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

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

// Public routes (no authentication required)
Route::post('/login', [ApiController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Transfer routes
    // Route::post('/transfer', [ApiController::class, 'transfer']);

    // Transaction routes
    Route::post('/fetch-transaction-detail', [ApiController::class, 'fetchTXDetail']);

    // Wallet routes
    Route::post('/fetch-wallet', [ApiController::class, 'fetchWallet']);
    Route::post('/bank-transfer', [ApiController::class, 'transfer']);

});

// Fallback for undefined routes
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found'
    ], 404);
});

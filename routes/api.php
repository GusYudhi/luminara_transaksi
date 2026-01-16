<?php

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PosTransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Auth
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // POS Transactions (Cloud)
    Route::get('/pos/transactions', [PosTransactionController::class, 'index']);
    Route::post('/pos/transactions', [PosTransactionController::class, 'store']);
    Route::get('/pos/transactions/{uuid}', [PosTransactionController::class, 'show']);
});

// Payment Gateway (Midtrans) - Existing
Route::post('/transaction', [PaymentController::class, 'createTransaction']);
Route::get('/transaction/{orderId}', [PaymentController::class, 'checkStatus']);
Route::post('/transaction/{orderId}/sync', [PaymentController::class, 'syncStatus']);
Route::post('/midtrans-callback', [PaymentController::class, 'handleWebhook']);

<?php

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\BriQrisController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/transaction', [PaymentController::class, 'createTransaction']);
Route::get('/transaction/{orderId}', [PaymentController::class, 'checkStatus']);
Route::post('/transaction/{orderId}/sync', [PaymentController::class, 'syncStatus']);
Route::post('/midtrans-callback', [PaymentController::class, 'handleWebhook']);

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Endpoint untuk Finish Redirect dari Midtrans
Route::get('/payment-finish', function () {
    return view('payment_finish');
});
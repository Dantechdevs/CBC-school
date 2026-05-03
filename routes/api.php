<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MpesaController;
use App\Http\Controllers\Api\KemisController;

Route::get('/user', fn(Request $request) => $request->user())->middleware('auth:sanctum');

// M-Pesa callbacks (no auth - called by Safaricom)
Route::prefix('mpesa')->group(function () {
    Route::post('/callback',     [MpesaController::class, 'callback']);
    Route::post('/confirmation', [MpesaController::class, 'confirmation']);
    Route::post('/validation',   [MpesaController::class, 'validation']);
    Route::post('/stk-push',     [MpesaController::class, 'stkPush'])->middleware('auth:sanctum');
});

// KEMIS API
Route::prefix('kemis')->middleware('auth:sanctum')->group(function () {
    Route::post('/sync',         [KemisController::class, 'sync']);
    Route::get('/status',        [KemisController::class, 'status']);
});

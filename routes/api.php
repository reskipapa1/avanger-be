<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:3,1');

Route::get('/bank', [BankController::class, 'index']);
Route::get('/bank/{kode_bank}', [BankController::class, 'show']);
Route::post('/bank', [BankController::class, 'store']);
Route::put('/bank/{kode_bank}', [BankController::class, 'update']);
Route::delete('/bank/{kode_bank}', [BankController::class, 'destroy']);

// ============ PROTECTED ROUTES (Token + Expiry + Abilities) ============
Route::middleware(['auth:sanctum', 'check.token.expiry'])->group(function () {

    // ---- hanya bisa read user (token butuh abilities: user:read)
    Route::get('/user', function(Request $request) {
        return $request->user();
    })->middleware('abilities:user:read');

    // ---- hanya bisa logout (token butuh abilities: user:write)
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('abilities:user:write');

});

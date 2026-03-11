<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\SecurityController;

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

// Domain Configuration (Ensure 'api' prefix is handled or removed in bootstrap/app.php if you want clean api.saldo.com.co/)
$apiDomain = 'api.saldo.com.co';

Route::domain($apiDomain)->group(function () {
    
    Route::middleware('throttle:10,1')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/verify-pin', [AuthController::class, 'verifyPin']);
        Route::prefix('security')->group(function () {
            Route::post('/2fa/sms/enable', [SecurityController::class, 'enableSms']);
            Route::post('/2fa/sms/verify', [SecurityController::class, 'verifySms']);
            Route::post('/2fa/totp/enable', [SecurityController::class, 'enableTotp']);
            Route::post('/2fa/totp/verify', [SecurityController::class, 'verifyTotp']);
            Route::post('/2fa/disable', [SecurityController::class, 'disable2fa']);
        });

        // Wallet Routes
        Route::get('/wallet/balance', [WalletController::class, 'index']);
        Route::post('/wallet/transfer', [WalletController::class, 'transfer']);
        Route::post('/wallet/exchange', [WalletController::class, 'exchange']);
        Route::get('/wallet/transactions', [WalletController::class, 'transactions']);

        // Admin Routes (Should have stricter middleware like 'admin')
        Route::prefix('admin')->group(function () {
            Route::get('/users', [AdminController::class, 'users']);
            Route::get('/kyc/requests', [AdminController::class, 'kycRequests']);
            Route::post('/kyc/{id}/approve', [AdminController::class, 'approveKyc']);
            Route::post('/kyc/{id}/reject', [AdminController::class, 'rejectKyc']);
            Route::post('/users/{id}/freeze', [AdminController::class, 'freezeAccount']);
        });
    });

    Route::prefix('v2')->middleware(['jwt.auth'])->group(function () {
        Route::get('/wallet/balance', [WalletController::class, 'index']);
        Route::post('/wallet/transfer', [WalletController::class, 'transfer']);
        Route::post('/wallet/exchange', [WalletController::class, 'exchange']);
        Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
    });
});

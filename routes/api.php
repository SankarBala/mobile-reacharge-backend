<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\WalletUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::apiResource('/recharge', TopupController::class)->names('recharge')->middleware('auth:sanctum');



Route::get('/pay/{amount}', [WalletUpController::class, 'createPayment'])->middleware('auth:sanctum');
Route::get('/payNow/{txid}', [WalletUpController::class, 'payNow'])->name('payNow');
Route::get('/payment-check', [WalletUpController::class, 'paymentResponse'])->name('paymentResponse');

<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Sale\SaleController;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/get-sales', [SaleController::class, 'getSales'])->middleware(['auth:sanctum', 'ability:web-access']);

Route::prefix('mobile')->middleware(['auth:sanctum', 'ability:app-access'])->group(function () {
    Route::get('/get-sales', [SaleController::class, 'getSalesFromSeller']);
    Route::post('/place-sales', [SaleController::class, 'placeSale']);
});

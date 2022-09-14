<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\TransactionController;
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

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('savings')->group(function () {
        Route::get('/', [SavingsController::class, 'index'])->name('list');
        Route::get('/{user_id}', [SavingsController::class, 'show'])->name('detail');
        Route::post('/store', [SavingsController::class, 'store'])->name('store');
    });

    Route::prefix('transactions')->group(function() {
        Route::get('/', [TransactionController::class, 'index'])->name('list');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('detail');
    });
});


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CableController;
use App\Http\Controllers\AuthController;

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


Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('profile', [AuthController::class, 'profile']);


    Route::get('cables', [CableController::class, 'index']);
    Route::get('cable/{id}', [CableController::class, 'getOne']);
    Route::post('cable/create', [CableController::class, 'store']);
    Route::put('cable/update/{id}', [CableController::class, 'update']);
    Route::delete('/delete/{id}', [CableController::class, 'destroy']);


    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('admin/user')->group(function() {
        Route::put('/update/{id}', [AdminController::class, 'update']);
        Route::delete('/delete/{id}', [AdminController::class, 'destroy']);
    });
});


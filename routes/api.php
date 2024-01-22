<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\StatisticController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CableController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DebtorDetailController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('profile', [AuthController::class, 'profile']);

    Route::prefix('cable')->group(function() {
        Route::get('/', [CableController::class, 'index']);
        Route::post('/create', [CableController::class, 'store']);
        Route::put('/update/{id}', [CableController::class, 'update']);
        Route::delete('/delete/{id}', [CableController::class, 'destroy']);
    });


    Route::post('refreshToken', [AuthController::class, 'refreshToken'])
        ->middleware(['ability:'.TokenAbility::ISSUE_ACCESS_TOKEN->value]);

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

});


<?php

use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\TransactionController;
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

Route::namespace('API')->group(function () {
    Route::post('/transfer', [TransactionController::class, 'transfer']);
    Route::post('/deposit', [TransactionController::class, 'deposit']);
    Route::get('/balance/{id}', [AccountController::class, 'balance'])->where(['id' => '[0-9]+']);
});

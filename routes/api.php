<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\TransferController;


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

// Public routes

//User routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//User routes
Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::post('users/search', [UserController::class, 'search']);

//charges routes
    Route::get('charges', [ChargeController::class, 'index']);
    Route::get('charges/{id}', [ChargeController::class, 'show']);
    Route::post('charges', [ChargeController::class, 'store']);
    Route::put('charges/{id}', [ChargeController::class, 'update']);
    Route::delete('charges/{id}', [ChargeController::class, 'destroy']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //User routes
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::post('change-pin', [UserController::class, 'changeTransactionPin']);
    Route::post('change-password', [UserController::class, 'changePassword']);

    //account routes
    Route::get('accounts', [AccountController::class, 'index']);
    Route::get('accounts/{id}', [AccountController::class, 'show']);
    Route::post('accounts', [AccountController::class, 'store']);
    //Route::put('accounts/{id}', [AccountController::class, 'update']);
    Route::delete('accounts/{id}', [AccountController::class, 'destroy']);
    
    //transfer routes
    Route::get('transfers', [TransferController::class, 'index']);
    Route::get('transfers/{id}', [TransferController::class, 'show']);
    Route::post('transfers', [TransferController::class, 'store']);
    
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    
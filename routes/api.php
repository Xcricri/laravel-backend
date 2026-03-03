<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ContactMessageController;

//AUTH
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');
});

//PUBLIC ROUTES
Route::get('/pages', [PageController::class, 'index']);
Route::get('/pages/{id}', [PageController::class, 'show']);

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/portfolios', [PortfolioController::class, 'index']);
Route::get('/portfolios/{id}', [PortfolioController::class, 'show']);

Route::post('/contact-messages', [ContactMessageController::class, 'store']);

//ADMIN ROUTES
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('users', UserController::class);

    Route::apiResource('pages', PageController::class)->except(['index', 'show']);
    Route::apiResource('services', ServiceController::class)->except(['index', 'show']);
    Route::apiResource('portfolios', PortfolioController::class)->except(['index', 'show']);

    Route::get('/contact-messages', [ContactMessageController::class, 'index']);
    Route::delete('/contact-messages/{id}', [ContactMessageController::class, 'destroy']);
});

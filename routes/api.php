<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ContactMessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Auth untuk API tester
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');
});


//PUBLIC ROUTES

// Page //
Route::get('/pages', [PageController::class, 'index']); // untuk navigasi publik
Route::get('/pages/{slug}', [PageController::class, 'show']); // detail halaman by slug

// Portofolio //
Route::get('/portfolios', [PortfolioController::class, 'index']); // list portfolio
Route::get('/portfolios/{slug}', [PortfolioController::class, 'show']); // detail portfolio

// Service //
Route::get('/services', [ServiceController::class, 'index']); // list service
Route::get('/services/{slug}', [ServiceController::class, 'show']); // detail service

// Message //
Route::post('/messages', [ContactMessageController::class, 'store']); // kirim pesan


//ADMIN ROUTES
Route::middleware('auth:sanctum')->group(function () {

    // User //
    Route::apiResource('users', UserController::class);
    // Mengambil data user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Page //
    Route::get('/admin/pages/{id}', [PageController::class, 'indexId']); // get page by ID
    Route::apiResource('admin/pages', PageController::class);

    // Portofolio //
    Route::get('/admin/portfolios/{id}', [PortfolioController::class, 'indexId']); // get portfolio by ID
    Route::apiResource('admin/portfolios', PortfolioController::class);

    // Service //
    Route::get('/admin/services/{id}', [ServiceController::class, 'indexId']); // get service by ID
    Route::apiResource('admin/services', ServiceController::class);

    // Message //
    Route::apiResource('admin/messages', ContactMessageController::class);
});

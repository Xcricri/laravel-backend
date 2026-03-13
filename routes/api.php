<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//AUTH
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');
});

//PUBLIC ROUTES

// page //
Route::get('/pages', [PageController::class, 'publicIndex']); // untuk navigasi publik
Route::get('/pages/{slug}', [PageController::class, 'show']); // detail halaman by slug

// Portofolio //
Route::get('/portfolios', [PortfolioController::class, 'index']); // list portfolio
Route::get('/portfolios/{slug}', [PortfolioController::class, 'show']); // detail portfolio

// Service //
Route::get('/services', [ServiceController::class, 'index']); // list service
Route::get('/services/{slug}', [ServiceController::class, 'show']); // detail service


//ADMIN ROUTES
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User //
    Route::apiResource('users', UserController::class);

    // Page //
    Route::get('/admin/pages', [PageController::class, 'index']); // list untuk admin
    Route::get('/admin/pages/{id}', [PageController::class, 'getId']); // get page by ID
    Route::post('/admin/pages', [PageController::class, 'store']); // create page
    Route::put('/admin/pages/{page}', [PageController::class, 'update']); // update page
    Route::delete('/admin/pages/{page}', [PageController::class, 'destroy']); // delete page

    // Portofolio //
    Route::get('/admin/portfolios', [PortfolioController::class, 'index']); // list untuk admin
    Route::get('/admin/portfolios/{id}', [PortfolioController::class, 'getId']); // get portfolio by ID
    Route::post('/admin/portfolios', [PortfolioController::class, 'store']); // create portfolio
    Route::put('/admin/portfolios/{portfolio}', [PortfolioController::class, 'update']); // update portfolio
    Route::delete('/admin/portfolios/{portfolio}', [PortfolioController::class, 'destroy']); // delete portfolio

    // Service //
    Route::get('/admin/services', [ServiceController::class, 'index']); // list untuk admin
    Route::get('/admin/services/{id}', [ServiceController::class, 'getId']); // get service by ID
    Route::post('/admin/services', [ServiceController::class, 'store']); // create service
    Route::put('/admin/services/{service}', [ServiceController::class, 'update']); // update service
    Route::delete('/admin/services/{service}', [ServiceController::class, 'destroy']); // delete service

});

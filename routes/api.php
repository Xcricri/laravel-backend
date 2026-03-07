<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//AUTH
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');
});

//PUBLIC ROUTES
Route::get('/pages', [PageController::class, 'publicIndex']); // untuk navigasi publik
Route::get('/pages/{slug}', [PageController::class, 'show']); // detail halaman by slug

//ADMIN ROUTES
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User
    Route::apiResource('users', UserController::class);

    // Page
    Route::get('/admin/pages', [PageController::class, 'adminIndex']); // list untuk admin
    Route::get('/admin/pages/{page}/edit', [PageController::class, 'edit']); // ambil page untuk edit
    Route::post('/admin/pages', [PageController::class, 'store']); // create page
    Route::put('/admin/pages/{page}', [PageController::class, 'update']); // update page
    Route::delete('/admin/pages/{page}', [PageController::class, 'destroy']); // delete page
});

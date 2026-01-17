<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('v1')->group(function () {
    // Stats (for dashboard)
    Route::get('/stats', [StatsController::class, 'index']);

    // News
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/{id}', [NewsController::class, 'show']);

    // Contact
    Route::post('/contact', [ContactController::class, 'store']);

    // Testimonials (only approved)
    Route::get('/testimonials', [TestimonialController::class, 'index']);
    Route::post('/testimonials', [TestimonialController::class, 'store']); // Public submission

    // Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);

    // Gallery
    Route::get('/gallery', [GalleryController::class, 'index']);
    Route::get('/gallery/{id}', [GalleryController::class, 'show']);
    Route::get('/gallery/events/list', [GalleryController::class, 'events']);

    // Authentication
    Route::post('/admin/login', [AuthController::class, 'login']);
});

// Protected admin routes
Route::prefix('v1/admin')->middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // News Management
    Route::middleware('role:news')->group(function () {
        Route::post('/news', [NewsController::class, 'store']);
        Route::put('/news/{id}', [NewsController::class, 'update']);
        Route::delete('/news/{id}', [NewsController::class, 'destroy']);
    });

    // Contact Management
    Route::middleware('role:contacts')->group(function () {
        Route::get('/contacts', [ContactController::class, 'index']);
        Route::put('/contacts/{id}', [ContactController::class, 'update']);
    });

    // Testimonials Management
    Route::middleware('role:testimonials')->group(function () {
        Route::get('/testimonials/all', [TestimonialController::class, 'all']);
        Route::put('/testimonials/{id}', [TestimonialController::class, 'update']);
        Route::delete('/testimonials/{id}', [TestimonialController::class, 'destroy']);
    });

    // Projects Management
    Route::middleware('role:projects')->group(function () {
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::put('/projects/{id}', [ProjectController::class, 'update']);
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    });

    // Gallery Management
    Route::middleware('role:gallery')->group(function () {
        Route::post('/gallery', [GalleryController::class, 'store']);
        Route::put('/gallery/{id}', [GalleryController::class, 'update']);
        Route::delete('/gallery/{id}', [GalleryController::class, 'destroy']);
    });

    // User Management (Super Admin only)
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::put('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
        Route::put('/users/{id}/roles', [UserController::class, 'updateRoles']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::post('/change-password', [UserController::class, 'updatePassword']);
    });
});

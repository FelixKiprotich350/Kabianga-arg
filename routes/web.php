<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonPagesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Minimal web routes for API-only application.
| All main functionality is available through API endpoints.
|
*/

// Initial admin setup (can be removed after setup)
Route::get('/setupadmin', [CommonPagesController::class, 'setupadmin'])->name('pages.setupadmin');
Route::post('/setupadmin', [CommonPagesController::class, 'makeInitialAdmin'])->name('api.makeinitialadmin');

// Default route - API information
Route::get('/', function () {
    return response()->json([
        'message' => 'Kabianga ARG Portal API',
        'version' => '1.0.0',
        'documentation' => url('/docs/api'),
        'endpoints' => [
            'auth' => '/api/v1/auth/*',
            'proposals' => '/api/v1/proposals/*',
            'projects' => '/api/v1/projects/*',
            'users' => '/api/v1/users/*',
            'reports' => '/api/v1/reports/*'
        ]
    ]);
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});


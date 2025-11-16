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
        'documentation' => url('/api/documentation'),
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

// Swagger documentation route
Route::get('/api/documentation', function () {
    $swaggerJson = file_get_contents(storage_path('api-docs/api-docs.json'));
    $swaggerData = json_decode($swaggerJson, true);
    
    return view('swagger-ui', compact('swaggerData'));
});

// Swagger JSON endpoint
Route::get('/api/docs.json', function () {
    return response()->file(storage_path('api-docs/api-docs.json'));
});
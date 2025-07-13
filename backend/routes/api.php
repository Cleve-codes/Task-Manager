<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailPreferencesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'app' => config('app.name'),
        'version' => '1.0.1-csrf-fix',
        'csrf_disabled' => true
    ]);
});

// Test route for API functionality
Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working correctly!',
        'csrf_disabled' => 'CSRF protection is disabled for API routes',
        'timestamp' => now(),
        'test_endpoints' => [
            'POST /api/register' => 'Create new user account',
            'POST /api/login' => 'Login with email/password',
            'GET /api/user' => 'Get current user (requires auth)',
        ]
    ]);
});

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes (authentication required)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Additional task-specific routes
    Route::get('/my-tasks', [TaskController::class, 'myTasks']);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);

    // Email preferences routes
    Route::get('/email-preferences', [EmailPreferencesController::class, 'index']);
    Route::put('/email-preferences', [EmailPreferencesController::class, 'update']);

    // Admin email preferences routes
    Route::get('/users/{id}/email-preferences', [EmailPreferencesController::class, 'show']);
    Route::put('/users/{id}/email-preferences', [EmailPreferencesController::class, 'updateUserPreferences']);
    Route::get('/admin/email-preferences/overview', [EmailPreferencesController::class, 'overview']);

    /**
     * @OA\Get(
     *     path="/api/user",
     *     tags={"Authentication"},
     *     summary="Get current user",
     *     description="Get authenticated user information",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Current user information",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

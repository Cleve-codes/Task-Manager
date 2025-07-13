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
        'version' => '1.0.0'
    ]);
});

// TEMPORARY EMAIL TEST ROUTE - REMOVE AFTER TESTING
Route::post('/test-email', function (Request $request) {
    try {
        $email = $request->input('email', 'test@example.com');

        // Test basic mail sending
        \Illuminate\Support\Facades\Mail::raw('This is a test email from your Task Management System.', function ($message) use ($email) {
            $message->to($email)
                    ->subject('Test Email from Task Management System')
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });

        return response()->json([
            'message' => 'Test email sent successfully!',
            'email' => $email,
            'mail_config' => [
                'mailer' => config('mail.default'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
                'queue_connection' => config('queue.default')
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to send test email',
            'message' => $e->getMessage(),
            'mail_config' => [
                'mailer' => config('mail.default'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
                'queue_connection' => config('queue.default')
            ]
        ], 500);
    }
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

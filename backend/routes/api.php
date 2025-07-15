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

// Mailgun test route (for deployment testing)
Route::post('/test-mailgun', function (Request $request) {
    $request->validate([
        'email' => 'required|email'
    ]);

    $email = $request->input('email');
    $domain = config('services.mailgun.domain');
    $apiKey = config('services.mailgun.secret');
    $endpoint = config('services.mailgun.endpoint', 'api.mailgun.net');

    // Validate configuration
    if (!$domain || !$apiKey) {
        return response()->json([
            'success' => false,
            'message' => 'Mailgun configuration is incomplete',
            'config' => [
                'domain' => $domain ? 'SET' : 'NOT SET',
                'api_key' => $apiKey ? 'SET' : 'NOT SET',
                'endpoint' => $endpoint
            ]
        ], 500);
    }

    try {
        $response = \Illuminate\Support\Facades\Http::withBasicAuth('api', $apiKey)
            ->asForm()
            ->post("https://{$endpoint}/v3/{$domain}/messages", [
                'from' => "Test <noreply@{$domain}>",
                'to' => $email,
                'subject' => 'Mailgun Test - Task Management System (Production)',
                'text' => 'This is a test email sent from your DEPLOYED Task Management System via Mailgun API.',
                'html' => '<h1>🚀 Production Mailgun Test</h1><p>This email was sent from your <strong>deployed Task Management System</strong> via Mailgun API.</p><p>✅ Your production email configuration is working correctly!</p>',
            ]);

        if ($response->successful()) {
            $responseData = $response->json();
            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully from production!',
                'data' => [
                    'message_id' => $responseData['id'] ?? 'N/A',
                    'status' => $responseData['message'] ?? 'Email queued for delivery',
                    'domain' => $domain,
                    'endpoint' => $endpoint
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email',
                'error' => [
                    'status_code' => $response->status(),
                    'response' => $response->body()
                ]
            ], 400);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Exception occurred',
            'error' => $e->getMessage()
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

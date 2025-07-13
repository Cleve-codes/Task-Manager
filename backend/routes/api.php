<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailPreferencesController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

// TEMPORARY DIAGNOSTIC ROUTE - REMOVE AFTER SETUP
Route::get('/setup-check', function () {
    try {
        $checks = [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'database_connection' => config('database.default'),
            'users_table_exists' => \Illuminate\Support\Facades\Schema::hasTable('users'),
            'admin_exists' => false,
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version()
        ];

        if ($checks['users_table_exists']) {
            $checks['admin_exists'] = User::where('role', 'admin')->exists();
            $checks['total_users'] = User::count();
        }

        return response()->json([
            'status' => 'System check completed',
            'checks' => $checks
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'System check failed',
            'error' => $e->getMessage()
        ], 500);
    }
});

// TEMPORARY ROUTE - REMOVE AFTER CREATING ADMIN USER
Route::post('/setup-admin', function (Request $request) {
    try {
        // Check if users table exists and run migrations if needed
        try {
            \Illuminate\Support\Facades\Schema::hasTable('users');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database not properly set up',
                'message' => 'Please run migrations first',
                'suggestion' => 'Contact your hosting provider to run: php artisan migrate'
            ], 500);
        }

        // Check if admin already exists
        $existingAdmin = User::where('role', 'admin')->first();

        if ($existingAdmin) {
            return response()->json([
                'message' => 'Admin user already exists',
                'admin' => [
                    'name' => $existingAdmin->name,
                    'email' => $existingAdmin->email,
                    'role' => $existingAdmin->role
                ]
            ], 409);
        }

        // Get credentials from request or use defaults
        $name = $request->input('name', 'Admin User');
        $email = $request->input('email', 'admin@example.com');
        $password = $request->input('password', 'password');

        // Validate input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'error' => 'Invalid email format'
            ], 400);
        }

        if (strlen($password) < 6) {
            return response()->json([
                'error' => 'Password must be at least 6 characters'
            ], 400);
        }

        // Create admin user
        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'message' => 'Admin user created successfully!',
            'admin' => [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role
            ],
            'credentials' => [
                'email' => $email,
                'password' => $password
            ],
            'warning' => 'Please change the password after first login and REMOVE this route!'
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server error occurred',
            'message' => $e->getMessage(),
            'suggestion' => 'Check server logs for more details'
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

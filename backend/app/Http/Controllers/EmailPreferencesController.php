<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmailPreferencesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/email-preferences",
     *     tags={"Email Preferences"},
     *     summary="Get current user's email preferences",
     *     description="Get the authenticated user's email notification preferences",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Email preferences retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="task_assigned", type="boolean", example=true),
     *             @OA\Property(property="task_updated", type="boolean", example=true),
     *             @OA\Property(property="task_reminders", type="boolean", example=true),
     *             @OA\Property(property="welcome_email", type="boolean", example=true),
     *             @OA\Property(property="password_reset", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index()
    {
        $user = Auth::user();
        return response()->json($user->email_preferences ?? $this->getDefaultPreferences());
    }

    /**
     * @OA\Put(
     *     path="/api/email-preferences",
     *     tags={"Email Preferences"},
     *     summary="Update current user's email preferences",
     *     description="Update the authenticated user's email notification preferences",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="task_assigned", type="boolean", example=true),
     *             @OA\Property(property="task_updated", type="boolean", example=true),
     *             @OA\Property(property="task_reminders", type="boolean", example=true),
     *             @OA\Property(property="welcome_email", type="boolean", example=true),
     *             @OA\Property(property="password_reset", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email preferences updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Email preferences updated successfully"),
     *             @OA\Property(property="preferences", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'task_assigned' => 'sometimes|boolean',
            'task_updated' => 'sometimes|boolean',
            'task_reminders' => 'sometimes|boolean',
            'welcome_email' => 'sometimes|boolean',
            'password_reset' => 'sometimes|boolean',
        ]);

        $user = Auth::user();
        $currentPreferences = $user->email_preferences ?? $this->getDefaultPreferences();
        
        // Merge with existing preferences
        $newPreferences = array_merge($currentPreferences, $validated);
        
        $user->email_preferences = $newPreferences;
        $user->save();

        return response()->json([
            'message' => 'Email preferences updated successfully',
            'preferences' => $newPreferences
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}/email-preferences",
     *     tags={"Email Preferences"},
     *     summary="Get user's email preferences (Admin only)",
     *     description="Get email notification preferences for a specific user (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="User ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email preferences retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Admin only"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function show(string $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $user = User::findOrFail($id);
        return response()->json($user->email_preferences ?? $this->getDefaultPreferences());
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}/email-preferences",
     *     tags={"Email Preferences"},
     *     summary="Update user's email preferences (Admin only)",
     *     description="Update email notification preferences for a specific user (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="User ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="task_assigned", type="boolean"),
     *             @OA\Property(property="task_updated", type="boolean"),
     *             @OA\Property(property="task_reminders", type="boolean"),
     *             @OA\Property(property="welcome_email", type="boolean"),
     *             @OA\Property(property="password_reset", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email preferences updated successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Admin only"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function updateUserPreferences(Request $request, string $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'task_assigned' => 'sometimes|boolean',
            'task_updated' => 'sometimes|boolean',
            'task_reminders' => 'sometimes|boolean',
            'welcome_email' => 'sometimes|boolean',
            'password_reset' => 'sometimes|boolean',
        ]);

        $user = User::findOrFail($id);
        $currentPreferences = $user->email_preferences ?? $this->getDefaultPreferences();
        
        // Merge with existing preferences
        $newPreferences = array_merge($currentPreferences, $validated);
        
        $user->email_preferences = $newPreferences;
        $user->save();

        return response()->json([
            'message' => 'Email preferences updated successfully',
            'preferences' => $newPreferences,
            'user' => $user->only(['id', 'name', 'email'])
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/email-preferences/overview",
     *     tags={"Email Preferences"},
     *     summary="Get email preferences overview (Admin only)",
     *     description="Get overview of all users' email notification preferences (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Email preferences overview retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Admin only"
     *     )
     * )
     */
    public function overview()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $users = User::select('id', 'name', 'email', 'email_preferences')->get();
        
        $overview = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'preferences' => $user->email_preferences ?? $this->getDefaultPreferences()
            ];
        });

        // Calculate statistics
        $stats = $this->calculatePreferenceStats($users);

        return response()->json([
            'users' => $overview,
            'statistics' => $stats
        ]);
    }

    /**
     * Get default email preferences
     */
    private function getDefaultPreferences(): array
    {
        return [
            'task_assigned' => true,
            'task_updated' => true,
            'task_reminders' => true,
            'welcome_email' => true,
            'password_reset' => true,
        ];
    }

    /**
     * Calculate preference statistics
     */
    private function calculatePreferenceStats($users): array
    {
        $totalUsers = $users->count();
        $defaultPreferences = $this->getDefaultPreferences();
        
        $stats = [];
        
        foreach ($defaultPreferences as $preference => $defaultValue) {
            $enabledCount = $users->filter(function ($user) use ($preference) {
                $preferences = $user->email_preferences ?? $this->getDefaultPreferences();
                return $preferences[$preference] ?? true;
            })->count();
            
            $stats[$preference] = [
                'enabled' => $enabledCount,
                'disabled' => $totalUsers - $enabledCount,
                'percentage' => $totalUsers > 0 ? round(($enabledCount / $totalUsers) * 100, 1) : 0
            ];
        }
        
        return $stats;
    }
}

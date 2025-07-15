<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Traits\ApiResponse;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    use ApiResponse;
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Get all tasks",
     *     description="Get all tasks (admin sees all, users see only assigned tasks)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Tasks retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tasks retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Task")
     *             )
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

        if ($user->role === 'admin') {
            // Admin sees all tasks
            $tasks = Task::with('user')->get();
        } else {
            // Regular users see only their assigned tasks
            $tasks = Task::with('user')->where('assigned_to', $user->id)->get();
        }

        return $this->successResponse($tasks, 'Tasks retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Create a new task",
     *     description="Create and assign a new task (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Task created and assigned successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Admin only"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $task = Task::create($validated);

        // Load the relationship for response
        $task->load('user');

        // Send email notification with error handling
        try {
            $user = User::find($validated['assigned_to']);
            if ($user) {
                $user->notify(new TaskAssignedNotification($task));
            }
        } catch (\Exception $e) {
            // Log the email error but don't fail the task creation
            Log::error('Failed to send task assignment email', [
                'task_id' => $task->id,
                'user_id' => $validated['assigned_to'],
                'error' => $e->getMessage()
            ]);
        }

        return $this->successResponse($task, 'Task created and assigned successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Get task by ID",
     *     description="Get a specific task by ID (Admin or assigned user only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Task retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Can only view assigned tasks",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You can only view your assigned tasks")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Task not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function show(Task $task)
    {
        $user = Auth::user();

        // Check if user can view this task
        if ($user->role !== 'admin' && $user->id !== $task->assigned_to) {
            return $this->forbiddenResponse('You can only view your assigned tasks');
        }

        $task->load('user');
        return $this->successResponse($task, 'Task retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Update task",
     *     description="Update a task (Admin can update all fields, users can only update status)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Task Title"),
     *             @OA\Property(property="description", type="string", example="Updated task description"),
     *             @OA\Property(property="status", type="string", enum={"Pending", "In Progress", "Completed"}, example="In Progress"),
     *             @OA\Property(property="assigned_to", type="integer", example=2, description="Admin only"),
     *             @OA\Property(property="deadline", type="string", format="date-time", example="2025-07-25T15:00:00Z", description="Admin only")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Task updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Insufficient permissions",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        // Store original values for change detection
        $originalValues = $task->only(array_keys($validated));

        $task->update($validated);

        // Load the relationship for response
        $task->load('user');

        // Send email notification if task was updated
        try {
            if ($task->user) {
                // Calculate what changed
                $changes = [];
                foreach ($validated as $key => $newValue) {
                    if ($originalValues[$key] != $newValue) {
                        $changes[$key] = [
                            'old' => $originalValues[$key],
                            'new' => $newValue
                        ];
                    }
                }

                if (!empty($changes)) {
                    $task->user->notify(new TaskUpdatedNotification($task, $changes));
                }
            }
        } catch (\Exception $e) {
            // Log the email error but don't fail the task update
            Log::error('Failed to send task update email', [
                'task_id' => $task->id,
                'user_id' => $task->assigned_to,
                'error' => $e->getMessage()
            ]);
        }

        return $this->successResponse($task, 'Task updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Delete task",
     *     description="Delete a task (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Task deleted successfully"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Admin only",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Only admins can delete tasks")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(Task $task)
    {
        if (Auth::user()->role !== 'admin') {
            return $this->forbiddenResponse('Only admins can delete tasks');
        }

        $task->delete();
        return $this->successResponse(null, 'Task deleted successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/my-tasks",
     *     tags={"Tasks"},
     *     summary="Get my assigned tasks",
     *     description="Get tasks assigned to the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Your assigned tasks retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Your assigned tasks retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Task")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function myTasks()
    {
        $user = Auth::user();
        $tasks = Task::with('user')->where('assigned_to', $user->id)->get();

        return $this->successResponse($tasks, 'Your assigned tasks retrieved successfully');
    }

    /**
     * @OA\Patch(
     *     path="/api/tasks/{task}/status",
     *     tags={"Tasks"},
     *     summary="Update task status",
     *     description="Update only the status of a task (Admin or assigned user)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskStatusUpdate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task status updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Task status updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Can only update assigned tasks"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function updateStatus(Request $request, Task $task)
    {
        $user = Auth::user();

        // Check if user can update this task
        if ($user->role !== 'admin' && $user->id !== $task->assigned_to) {
            return $this->forbiddenResponse('You can only update your assigned tasks');
        }

        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $task->update(['status' => $validated['status']]);
        $task->load('user');

        return $this->successResponse($task, 'Task status updated successfully');
    }
}

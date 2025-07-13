<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Task Management API",
 *     version="1.0.0",
 *     description="A comprehensive task management system with role-based access control",
 *     @OA\Contact(
 *         email="clevemomanyi@gmail.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Local Development Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter token in format: Bearer {token}"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="User authentication endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Tasks",
 *     description="Task management endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Users",
 *     description="User management endpoints (Admin only)"
 * )
 *
 * @OA\Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Operation successful"),
 *     @OA\Property(property="data", type="object")
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Error occurred"),
 *     @OA\Property(property="errors", type="object")
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="role", type="string", enum={"admin", "user"}, example="user"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1, description="Unique task identifier"),
 *     @OA\Property(property="title", type="string", example="Complete project documentation", description="Task title"),
 *     @OA\Property(property="description", type="string", example="Write comprehensive API documentation", description="Detailed task description"),
 *     @OA\Property(property="status", type="string", enum={"Pending", "In Progress", "Completed"}, example="Pending", description="Current task status"),
 *     @OA\Property(property="assigned_to", type="integer", example=2, description="ID of the user assigned to this task"),
 *     @OA\Property(property="deadline", type="string", format="date-time", example="2025-07-20T10:00:00Z", description="Task deadline"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-13T10:00:00Z", description="Task creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-13T10:00:00Z", description="Last update timestamp"),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User",
 *         description="User assigned to this task"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="TaskRequest",
 *     type="object",
 *     required={"title", "status", "assigned_to"},
 *     @OA\Property(property="title", type="string", example="Complete project documentation", description="Task title"),
 *     @OA\Property(property="description", type="string", example="Write comprehensive API documentation", description="Detailed task description"),
 *     @OA\Property(property="status", type="string", enum={"Pending", "In Progress", "Completed"}, example="Pending", description="Initial task status"),
 *     @OA\Property(property="assigned_to", type="integer", example=2, description="ID of the user to assign this task to"),
 *     @OA\Property(property="deadline", type="string", format="date-time", example="2025-07-20T10:00:00Z", description="Task deadline (optional)")
 * )
 *
 * @OA\Schema(
 *     schema="TaskStatusUpdate",
 *     type="object",
 *     required={"status"},
 *     @OA\Property(property="status", type="string", enum={"Pending", "In Progress", "Completed"}, example="In Progress", description="New task status")
 * )
 *
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com", description="User email address"),
 *     @OA\Property(property="password", type="string", format="password", example="password123", description="User password")
 * )
 *
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     type="object",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", example="John Doe", description="User full name"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com", description="User email address"),
 *     @OA\Property(property="password", type="string", format="password", example="password123", description="User password (minimum 6 characters)")
 * )
 *
 * @OA\Schema(
 *     schema="AuthResponse",
 *     type="object",
 *     @OA\Property(property="token", type="string", example="1|abcdef123456...", description="Bearer token for authentication"),
 *     @OA\Property(property="user", ref="#/components/schemas/User", description="Authenticated user information")
 * )
 */
abstract class Controller
{
    //
}

<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    /**
     * Get all role values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get role for validation rules
     */
    public static function validationRule(): string
    {
        return 'in:' . implode(',', self::values());
    }

    /**
     * Check if role has admin privileges
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Check if role is regular user
     */
    public function isUser(): bool
    {
        return $this === self::USER;
    }

    /**
     * Get role permissions
     */
    public function permissions(): array
    {
        return match($this) {
            self::ADMIN => [
                'create_tasks',
                'assign_tasks',
                'view_all_tasks',
                'update_all_tasks',
                'delete_tasks',
                'manage_users',
            ],
            self::USER => [
                'view_assigned_tasks',
                'update_task_status',
            ],
        };
    }
}

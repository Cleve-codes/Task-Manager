<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'Pending';
    case IN_PROGRESS = 'In Progress';
    case COMPLETED = 'Completed';

    /**
     * Get all status values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get status for validation rules
     */
    public static function validationRule(): string
    {
        return 'in:' . implode(',', self::values());
    }

    /**
     * Get status color for frontend
     */
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::IN_PROGRESS => 'blue',
            self::COMPLETED => 'green',
        };
    }

    /**
     * Check if status can be changed to another status
     */
    public function canChangeTo(TaskStatus $newStatus): bool
    {
        return match($this) {
            self::PENDING => in_array($newStatus, [self::IN_PROGRESS, self::COMPLETED]),
            self::IN_PROGRESS => in_array($newStatus, [self::PENDING, self::COMPLETED]),
            self::COMPLETED => in_array($newStatus, [self::PENDING, self::IN_PROGRESS]),
        };
    }
}

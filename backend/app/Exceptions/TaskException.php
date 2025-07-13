<?php

namespace App\Exceptions;

use Exception;

class TaskException extends Exception
{
    public static function notAssigned(): self
    {
        return new self('You are not assigned to this task', 403);
    }

    public static function cannotAssignToSelf(): self
    {
        return new self('You cannot assign tasks to yourself', 422);
    }

    public static function invalidStatusTransition(string $from, string $to): self
    {
        return new self("Cannot change task status from {$from} to {$to}", 422);
    }

    public static function deadlineInPast(): self
    {
        return new self('Task deadline cannot be in the past', 422);
    }
}

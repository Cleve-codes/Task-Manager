<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'assigned_to',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'status' => TaskStatus::class,
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Scopes
    public function scopeAssignedTo(Builder $query, int $userId): Builder
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByStatus(Builder $query, TaskStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('deadline', '<', now())
                    ->where('status', '!=', TaskStatus::COMPLETED);
    }

    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereDate('deadline', today());
    }

    // Accessors
    public function getIsOverdueAttribute(): bool
    {
        return $this->deadline &&
               $this->deadline < now() &&
               $this->status !== TaskStatus::COMPLETED;
    }

    public function getDaysUntilDeadlineAttribute(): ?int
    {
        return $this->deadline ? now()->diffInDays($this->deadline, false) : null;
    }
}

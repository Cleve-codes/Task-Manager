@extends('emails.layouts.app')

@section('title', 'Task Reminder')

@section('header-subtitle', 'Don\'t forget about your upcoming task')

@section('content')
    <h2>Task Reminder ⏰</h2>
    
    <p>Hello {{ $user->name }},</p>
    
    <p>This is a friendly reminder about your upcoming task deadline:</p>

    <div class="task-card">
        <div class="task-title">{{ $task->title }}</div>
        
        @if($task->description)
            <div class="task-description">{{ $task->description }}</div>
        @endif

        <div class="task-meta">
            <div>
                <span class="task-status status-{{ strtolower(str_replace([' ', '_'], '-', $task->status->value ?? $task->status)) }}">
                    {{ $task->status->value ?? $task->status }}
                </span>
            </div>
            @if($task->deadline)
                <div class="task-deadline">
                    Due: {{ $task->deadline->format('M j, Y \a\t g:i A') }}
                </div>
            @endif
        </div>
    </div>

    @if($task->deadline)
        @php
            $daysUntilDeadline = now()->diffInDays($task->deadline, false);
            $hoursUntilDeadline = now()->diffInHours($task->deadline, false);
        @endphp
        
        @if($daysUntilDeadline > 1)
            <div class="highlight">
                <p style="margin-bottom: 0; font-weight: 600;">
                    📅 This task is due in {{ $daysUntilDeadline }} days.
                </p>
            </div>
        @elseif($daysUntilDeadline === 1)
            <div class="highlight" style="border-left-color: #ffc107; background-color: #fff3cd;">
                <p style="margin-bottom: 0; font-weight: 600; color: #856404;">
                    ⚠️ This task is due tomorrow!
                </p>
            </div>
        @elseif($hoursUntilDeadline > 0)
            <div class="highlight" style="border-left-color: #fd7e14; background-color: #fff0e6;">
                <p style="margin-bottom: 0; font-weight: 600; color: #8a4a00;">
                    🚨 This task is due in {{ $hoursUntilDeadline }} {{ $hoursUntilDeadline === 1 ? 'hour' : 'hours' }}!
                </p>
            </div>
        @else
            <div class="highlight" style="border-left-color: #dc3545; background-color: #f8d7da;">
                <p style="margin-bottom: 0; font-weight: 600; color: #721c24;">
                    🔴 This task is overdue by {{ abs($daysUntilDeadline) }} {{ abs($daysUntilDeadline) === 1 ? 'day' : 'days' }}!
                </p>
            </div>
        @endif
    @endif

    @if($task->status->value !== 'COMPLETED')
        <p>Don't let this task slip through the cracks! Click the button below to work on it now.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.frontend_url', config('app.url')) }}/tasks/{{ $task->id }}" class="btn">
                Work on Task
            </a>
        </div>
    @else
        <p>Great job! This task has already been completed. This reminder was sent as a follow-up.</p>
    @endif

    <div style="margin-top: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
        <h3 style="margin-bottom: 12px; color: #1a1a1a; font-size: 16px;">💡 Productivity Tip</h3>
        <p style="margin-bottom: 0; color: #4a5568; font-size: 14px;">
            Break large tasks into smaller, manageable chunks. This makes them less overwhelming and helps you maintain momentum throughout the project.
        </p>
    </div>

    <p>Need help with this task? Don't hesitate to reach out to your team or administrator for assistance.</p>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e9ecef;">
        <p style="font-size: 14px; color: #6c757d; margin-bottom: 4px;">
            <strong>Task ID:</strong> #{{ $task->id }}
        </p>
        <p style="font-size: 14px; color: #6c757d; margin-bottom: 4px;">
            <strong>Reminder Sent:</strong> {{ now()->format('F j, Y \a\t g:i A') }}
        </p>
        @if($task->deadline)
            <p style="font-size: 14px; color: #6c757d; margin-bottom: 0;">
                <strong>Deadline:</strong> {{ $task->deadline->format('F j, Y \a\t g:i A') }}
            </p>
        @endif
    </div>
@endsection

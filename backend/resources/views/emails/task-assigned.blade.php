@extends('emails.layouts.app')

@section('title', 'New Task Assigned')

@section('header-subtitle', 'You have a new task to work on')

@section('content')
    <h2>New Task Assigned 📝</h2>
    
    <p>Hello {{ $user->name }},</p>
    
    <p>You have been assigned a new task. Here are the details:</p>

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
        @endphp
        
        @if($daysUntilDeadline >= 0)
            <div class="highlight">
                <p style="margin-bottom: 0; font-weight: 600;">
                    ⏰ This task is due in {{ $daysUntilDeadline }} {{ $daysUntilDeadline === 1 ? 'day' : 'days' }}.
                </p>
            </div>
        @else
            <div class="highlight" style="border-left-color: #dc3545; background-color: #f8d7da;">
                <p style="margin-bottom: 0; font-weight: 600; color: #721c24;">
                    ⚠️ This task is overdue by {{ abs($daysUntilDeadline) }} {{ abs($daysUntilDeadline) === 1 ? 'day' : 'days' }}.
                </p>
            </div>
        @endif
    @endif

    <p>Click the button below to view the task details and start working on it.</p>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ env('FRONTEND_URL', 'https://task-manager-lac-tau.vercel.app') }}/tasks/{{ $task->id }}" class="btn">
            View Task
        </a>
    </div>

    <p>If you have any questions about this task, please don't hesitate to reach out to your team lead or administrator.</p>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e9ecef;">
        <p style="font-size: 14px; color: #6c757d; margin-bottom: 4px;">
            <strong>Task ID:</strong> #{{ $task->id }}
        </p>
        <p style="font-size: 14px; color: #6c757d; margin-bottom: 4px;">
            <strong>Assigned Date:</strong> {{ $task->created_at->format('F j, Y \a\t g:i A') }}
        </p>
        @if($task->deadline)
            <p style="font-size: 14px; color: #6c757d; margin-bottom: 0;">
                <strong>Deadline:</strong> {{ $task->deadline->format('F j, Y \a\t g:i A') }}
            </p>
        @endif
    </div>
@endsection

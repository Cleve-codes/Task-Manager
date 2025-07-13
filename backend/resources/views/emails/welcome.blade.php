@extends('emails.layouts.app')

@section('title', 'Welcome to Task Management')

@section('header-subtitle', 'Welcome to your productivity journey')

@section('content')
    <h2>Welcome to Task Management, {{ $user->name }}! 🎉</h2>
    
    <p>We're thrilled to have you join our community of productive individuals. Your account has been successfully created and you're ready to start organizing your tasks like a pro.</p>

    <div class="highlight">
        <h3 style="margin-bottom: 12px; color: #1a1a1a; font-size: 18px;">Getting Started</h3>
        <p style="margin-bottom: 8px; color: #4a5568;">Here's what you can do with Task Management:</p>
        <ul style="color: #4a5568; margin-left: 20px; margin-bottom: 0;">
            <li>Create and organize your tasks efficiently</li>
            <li>Set deadlines and track your progress</li>
            <li>Collaborate with team members</li>
            <li>Receive timely reminders and notifications</li>
        </ul>
    </div>

    <p>Ready to dive in? Click the button below to access your dashboard and start managing your tasks.</p>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ config('app.frontend_url', config('app.url')) }}/dashboard" class="btn">
            Get Started
        </a>
    </div>

    <p>If you have any questions or need assistance, don't hesitate to reach out to our support team. We're here to help you succeed!</p>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e9ecef;">
        <p style="font-size: 14px; color: #6c757d; margin-bottom: 8px;">
            <strong>Account Details:</strong>
        </p>
        <p style="font-size: 14px; color: #6c757d; margin-bottom: 4px;">
            Email: {{ $user->email }}
        </p>
        <p style="font-size: 14px; color: #6c757d; margin-bottom: 0;">
            Registration Date: {{ $user->created_at->format('F j, Y') }}
        </p>
    </div>
@endsection

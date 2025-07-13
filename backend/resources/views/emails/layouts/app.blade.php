<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="x-apple-disable-message-reformatting">
    <title>@yield('title', 'Task Management')</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8f9fa;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #5865F2 0%, #4752C4 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .email-header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }

        .email-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin: 0;
        }

        .logo {
            width: 48px;
            height: 48px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #ffffff;
        }

        /* Content */
        .email-content {
            padding: 40px 30px;
        }

        .email-content h2 {
            color: #1a1a1a;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .email-content p {
            color: #4a5568;
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .email-content .highlight {
            background-color: #f7fafc;
            border-left: 4px solid #5865F2;
            padding: 20px;
            margin: 24px 0;
            border-radius: 0 8px 8px 0;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #5865F2 0%, #4752C4 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(88, 101, 242, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: #ffffff !important;
        }

        /* Task card */
        .task-card {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }

        .task-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .task-description {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .task-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .task-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-in-progress {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .task-deadline {
            color: #6c757d;
            font-weight: 500;
        }

        /* Footer */
        .email-footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .email-footer p {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .email-footer a {
            color: #5865F2;
            text-decoration: none;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                box-shadow: none;
            }

            .email-header,
            .email-content,
            .email-footer {
                padding: 30px 20px;
            }

            .email-header h1 {
                font-size: 24px;
            }

            .email-content h2 {
                font-size: 20px;
            }

            .task-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">📋</div>
            <h1>Task Management</h1>
            <p>@yield('header-subtitle', 'Stay organized and productive')</p>
        </div>

        <!-- Content -->
        <div class="email-content">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Task Management. All rights reserved.</p>
            <p>
                <a href="{{ config('app.url') }}">Visit our website</a> |
                <a href="{{ config('app.url') }}/unsubscribe?token={{ $user->email ?? '' }}">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>
</html>

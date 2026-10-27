<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'DevPulse' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #F8F9FA;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1E1E24;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #FFFFFF;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(125, 45, 104, 0.08);
            border: 1px solid #E9ECEF;
        }
        .header {
            background: linear-gradient(135deg, #7D2D68 0%, #5E214E 100%);
            padding: 36px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #FFFFFF;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 8px 0 0 0;
            color: #FFE5D9;
            font-size: 14px;
            font-weight: 500;
        }
        .content {
            padding: 36px 32px;
        }
        .btn-primary {
            display: inline-block;
            background-color: #7D2D68;
            color: #FFFFFF !important;
            text-decoration: none;
            padding: 14px 28px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(125, 45, 104, 0.25);
            margin: 20px 0;
        }
        .card-stat {
            background-color: #FDF9FB;
            border: 1px solid #F3E8EE;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 16px 0;
        }
        .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: #7D2D68;
        }
        .stat-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #6C757D;
            letter-spacing: 0.5px;
        }
        .footer {
            background-color: #F8F9FA;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #E9ECEF;
            font-size: 12px;
            color: #868E96;
        }
        .footer a {
            color: #7D2D68;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>⚡ DevPulse</h1>
            <p>La suite de productivité haute vélocité pour développeurs</p>
        </div>

        <!-- Body Content -->
        <div class="content">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 8px 0;">DevPulse · Chronomètre en temps réel, Kanban de sprint & Pomodoro</p>
            <p style="margin: 0;">
                <a href="{{ config('app.url') }}">devpulse.io</a> · 
                <a href="{{ config('app.url') }}/tasks">Mon Kanban</a> · 
                <a href="{{ config('app.url') }}/profile">Paramètres</a>
            </p>
        </div>
    </div>
</body>
</html>

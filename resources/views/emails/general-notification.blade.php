<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'Notification' }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2c3e50;">{{ $subject ?? 'Notification' }}</h2>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <p>{{ $content ?? 'Please check your email for further instructions.' }}</p>
            
            @if($actionUrl)
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{ $actionUrl }}" style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                        {{ $actionText ?? 'Click Here' }}
                    </a>
                </div>
            @endif
        </div>
        
        <p style="margin-top: 30px; font-size: 14px; color: #666;">
            Best regards,<br>
            {{ config('app.name') }} Team
        </p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #f9f9f9; }
        .button { display: inline-block; padding: 12px 24px; background: #2563eb; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>University of Kabianga - ARG Portal</h2>
        </div>
        <div class="content">
            <p>{{ $content }}</p>
            
            @if($actionUrl)
                <a href="{{ $actionUrl }}" class="button">{{ $actionText }}</a>
            @endif
        </div>
        <div class="footer">
            <p>Best regards,<br>University of Kabianga</p>
        </div>
    </div>
</body>
</html>
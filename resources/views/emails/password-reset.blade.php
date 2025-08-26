@extends('emails.layouts.master')

@section('title', 'Password Reset - ARG Portal')

@section('content')
    <div class="greeting">Hello {{ $notifiable->name }},</div>
    
    <div class="message">
        <p>You are receiving this email because we received a password reset request for your ARG Portal account.</p>
    </div>
    
    <div class="info-box" style="border-left-color: #f59e0b; background: #fffbeb;">
        <p><strong>Security Notice:</strong></p>
        <p>If you did not request a password reset, please ignore this email. Your password will remain unchanged.</p>
    </div>
    
    <div class="message">
        <p>To reset your password, click the button below. This link will expire in 60 minutes for security reasons.</p>
    </div>
    
    <a href="{{ $actionUrl }}" class="action-button">Reset Password</a>
    
    <div class="divider"></div>
    
    <div class="message">
        <p><strong>Security Tips:</strong></p>
        <ul style="margin-left: 20px; margin-top: 10px;">
            <li>Choose a strong, unique password</li>
            <li>Don't share your login credentials</li>
            <li>Log out when using shared computers</li>
            <li>Contact us if you notice suspicious activity</li>
        </ul>
    </div>
    
    <p style="font-size: 14px; color: #6b7280;">
        If you're having trouble clicking the button, copy and paste this URL into your browser:<br>
        <span style="word-break: break-all; color: #3b82f6;">{{ $actionUrl }}</span>
    </p>
@endsection
@extends('emails.layouts.master')

@section('title', 'Verify Your Account - ARG Portal')

@section('content')
    <div class="greeting">Hello {{ $user->name }},</div>
    
    <div class="message">
        <p>Thank you for registering with the University of Kabianga ARG Portal. Please verify your email address to activate your account.</p>
    </div>
    
    <div class="info-box" style="border-left-color: #10b981; background: #f0fdf4;">
        <p><strong>Account Verification Required</strong></p>
        <p>Click the button below to verify your email address and complete your registration.</p>
    </div>
    
    <a href="{{ $verificationUrl }}" class="action-button" style="background: #10b981;">Verify Email Address</a>
    
    <div class="message">
        <p>This verification link will expire in 24 hours. If you did not create an account, please ignore this email.</p>
    </div>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        If you're having trouble clicking the button, copy and paste this URL into your browser:<br>
        <span style="word-break: break-all; color: #3b82f6;">{{ $verificationUrl }}</span>
    </p>
@endsection
@extends('emails.layouts.master')

@section('title', 'Deadline Reminder - ARG Portal')

@section('content')
    <div class="greeting">Dear {{ $user->name }},</div>
    
    <div class="message">
        <p>This is a reminder that you have an upcoming deadline for <strong>{{ $item_type }}</strong>.</p>
    </div>
    
    <div class="info-box" style="border-left-color: #ef4444; background: #fef2f2;">
        <p><strong>Urgent: {{ $title }}</strong></p>
        <p>Due Date: {{ $due_date->format('F j, Y') }}</p>
        <p>Days Remaining: {{ $days_remaining }}</p>
    </div>
    
    <div class="message">
        <p>{{ $description }}</p>
    </div>
    
    <a href="{{ $action_url }}" class="action-button" style="background: #ef4444;">Take Action Now</a>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        Please ensure timely submission to avoid any complications with your {{ strtolower($item_type) }}.
    </p>
@endsection
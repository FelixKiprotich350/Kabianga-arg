@extends('emails.layouts.master')

@section('title', $subject ?? 'Notification - ARG Portal')

@section('content')
    <div class="greeting">{{ $greeting ?? 'Hello' }},</div>
    
    <div class="message">
        {!! $content !!}
    </div>
    
    @if(isset($actionUrl) && $actionUrl)
        <a href="{{ $actionUrl }}" class="action-button">{{ $actionText ?? 'Take Action' }}</a>
    @endif
    
    @if(isset($additionalInfo) && $additionalInfo)
        <div class="info-box">
            {!! $additionalInfo !!}
        </div>
    @endif
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        {{ $footer ?? 'This is an automated message from the ARG Portal. Please do not reply to this email.' }}
    </p>
@endsection
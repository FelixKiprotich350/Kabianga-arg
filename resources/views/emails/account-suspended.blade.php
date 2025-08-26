@extends('emails.layouts.master')

@section('title', 'Account Suspended - ARG Portal')

@section('content')
    <div class="greeting">Dear {{ $user->name }},</div>
    
    <div class="message">
        <p>Your ARG Portal account has been temporarily suspended due to {{ $reason ?? 'policy violations' }}.</p>
    </div>
    
    <div class="info-box" style="border-left-color: #ef4444; background: #fef2f2;">
        <p><strong>Account Status: Suspended</strong></p>
        <p>Suspension Date: {{ now()->format('F j, Y') }}</p>
        @if(isset($duration))
        <p>Duration: {{ $duration }}</p>
        @endif
    </div>
    
    @if(isset($details))
    <div class="message">
        <p><strong>Details:</strong></p>
        <p>{{ $details }}</p>
    </div>
    @endif
    
    <div class="message">
        <p>To appeal this decision or for more information, please contact the Research Office immediately.</p>
    </div>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        Contact: research@kabianga.ac.ke or visit the Research Office during business hours.
    </p>
@endsection
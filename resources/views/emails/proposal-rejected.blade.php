@extends('emails.layouts.master')

@section('title', 'Proposal Review Update - ARG Portal')

@section('content')
    <div class="greeting">Dear {{ $user->name }},</div>
    
    <div class="message">
        <p>Thank you for submitting your research proposal <strong>"{{ $proposal->title }}"</strong> to the Annual Research Grants Portal.</p>
    </div>
    
    <div class="info-box" style="border-left-color: #ef4444; background: #fef2f2;">
        <p><strong>Review Decision:</strong></p>
        <p>After careful evaluation by our review committee, we regret to inform you that your proposal was not selected for funding in this round.</p>
    </div>
    
    @if(isset($feedback) && $feedback)
    <div class="message">
        <p><strong>Reviewer Feedback:</strong></p>
        <div style="background: #f9fafb; padding: 15px; border-radius: 4px; margin: 15px 0;">
            {{ $feedback }}
        </div>
    </div>
    @endif
    
    <div class="message">
        <p><strong>Moving Forward:</strong></p>
        <ul style="margin-left: 20px; margin-top: 10px;">
            <li>Consider the feedback provided for future submissions</li>
            <li>You may resubmit an improved proposal in the next funding cycle</li>
            <li>Contact our office for guidance on strengthening your proposal</li>
            <li>Explore other funding opportunities that may be available</li>
        </ul>
    </div>
    
    <a href="{{ url('/proposals/create') }}" class="action-button">Submit New Proposal</a>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        We encourage you to continue your research efforts and consider reapplying in future funding rounds.
    </p>
@endsection
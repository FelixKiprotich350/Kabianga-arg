@extends('emails.layouts.master')

@section('title', 'Proposal Submitted - ARG Portal')

@section('content')
    <div class="greeting">Hello {{ $user->name }},</div>
    
    <div class="message">
        <p>Your research proposal <strong>"{{ $proposal->title }}"</strong> has been successfully submitted to the Annual Research Grants Portal.</p>
    </div>
    
    <div class="info-box">
        <p><strong>Proposal Details:</strong></p>
        <p>Title: {{ $proposal->title }}</p>
        <p>Submission Date: {{ $proposal->created_at->format('F j, Y') }}</p>
        <p>Status: Under Review</p>
    </div>
    
    <div class="message">
        <p>Your proposal is now under review by our evaluation committee. You will receive notifications about any status updates or requests for additional information.</p>
    </div>
    
    <a href="{{ url('/proposals/' . $proposal->id) }}" class="action-button">View Proposal</a>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        Thank you for your submission. If you have any questions, please contact the Research & Innovation Office.
    </p>
@endsection
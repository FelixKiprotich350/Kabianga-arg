@extends('emails.layouts.master')

@section('title', 'Report Submitted - ARG Portal')

@section('content')
    <div class="greeting">Hello {{ $user->name }},</div>
    
    <div class="message">
        <p>Your {{ $report_type }} for project <strong>"{{ $project->title }}"</strong> has been successfully submitted.</p>
    </div>
    
    <div class="info-box" style="border-left-color: #10b981; background: #f0fdf4;">
        <p><strong>Submission Details:</strong></p>
        <p>Report Type: {{ $report_type }}</p>
        <p>Submitted: {{ now()->format('F j, Y \a\t g:i A') }}</p>
        <p>Status: Under Review</p>
    </div>
    
    <div class="message">
        <p>Your report is now being reviewed by the Research Office. You will be notified of any feedback or approval status.</p>
    </div>
    
    <a href="{{ url('/projects/' . $project->id . '/reports') }}" class="action-button" style="background: #10b981;">View Reports</a>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        Thank you for your timely submission and continued commitment to your research project.
    </p>
@endsection
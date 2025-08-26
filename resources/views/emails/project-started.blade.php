@extends('emails.layouts.master')

@section('title', 'Project Started - ARG Portal')

@section('content')
    <div class="greeting">Congratulations {{ $user->name }}!</div>
    
    <div class="message">
        <p>Your research project <strong>"{{ $project->title }}"</strong> has officially started. You can now begin your research activities.</p>
    </div>
    
    <div class="info-box" style="border-left-color: #10b981; background: #f0fdf4;">
        <p><strong>Project Details:</strong></p>
        <p>Title: {{ $project->title }}</p>
        <p>Start Date: {{ $project->start_date->format('F j, Y') }}</p>
        <p>Duration: {{ $project->duration }} months</p>
        <p>Budget: KES {{ number_format($project->budget ?? 0) }}</p>
    </div>
    
    <div class="message">
        <p><strong>Important Reminders:</strong></p>
        <ul style="margin-left: 20px; margin-top: 10px;">
            <li>Submit progress reports as scheduled</li>
            <li>Maintain detailed financial records</li>
            <li>Follow ethical guidelines and protocols</li>
            <li>Acknowledge the University in publications</li>
        </ul>
    </div>
    
    <a href="{{ url('/projects/' . $project->id) }}" class="action-button" style="background: #10b981;">View Project Dashboard</a>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        Best wishes for a successful research project. We look forward to your contributions to knowledge and innovation.
    </p>
@endsection
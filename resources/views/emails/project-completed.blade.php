@extends('emails.layouts.master')

@section('title', 'Project Completed - ARG Portal')

@section('content')
    <div class="greeting">Congratulations {{ $user->name }}!</div>
    
    <div class="message">
        <p>Your research project <strong>"{{ $project->title }}"</strong> has been successfully completed. Thank you for your dedication and contribution to research at the University of Kabianga.</p>
    </div>
    
    <div class="info-box" style="border-left-color: #10b981; background: #f0fdf4;">
        <p><strong>Project Summary:</strong></p>
        <p>Title: {{ $project->title }}</p>
        <p>Duration: {{ $project->duration }} months</p>
        <p>Completion Date: {{ $project->end_date->format('F j, Y') }}</p>
        <p>Final Status: {{ ucfirst($project->status) }}</p>
    </div>
    
    <div class="message">
        <p><strong>Next Steps:</strong></p>
        <ul style="margin-left: 20px; margin-top: 10px;">
            <li>Submit your final research report</li>
            <li>Prepare publications from your findings</li>
            <li>Consider presenting at conferences</li>
            <li>Explore opportunities for follow-up research</li>
        </ul>
    </div>
    
    <a href="{{ url('/projects/' . $project->id . '/final-report') }}" class="action-button" style="background: #10b981;">Submit Final Report</a>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        We appreciate your commitment to advancing knowledge and research excellence at the University of Kabianga.
    </p>
@endsection
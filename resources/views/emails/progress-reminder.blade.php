@extends('emails.layouts.master')

@section('title', 'Progress Report Reminder - ARG Portal')

@section('content')
    <div class="greeting">Dear {{ $user->name }},</div>
    
    <div class="message">
        <p>This is a friendly reminder that a progress report for your research project <strong>"{{ $project->title }}"</strong> is due soon.</p>
    </div>
    
    <div class="info-box" style="border-left-color: #f59e0b; background: #fffbeb;">
        <p><strong>Report Details:</strong></p>
        <p>Project: {{ $project->title }}</p>
        <p>Due Date: {{ $dueDate->format('F j, Y') }}</p>
        <p>Report Type: {{ $reportType ?? 'Progress Report' }}</p>
        <p>Days Remaining: {{ $daysRemaining }}</p>
    </div>
    
    <div class="message">
        <p><strong>What to Include:</strong></p>
        <ul style="margin-left: 20px; margin-top: 10px;">
            <li>Research activities completed</li>
            <li>Key findings and results</li>
            <li>Challenges encountered and solutions</li>
            <li>Financial expenditure summary</li>
            <li>Plans for the next reporting period</li>
        </ul>
    </div>
    
    <div class="message">
        <p>Timely submission of progress reports is essential for continued funding and project monitoring.</p>
    </div>
    
    <a href="{{ url('/projects/' . $project->id . '/reports') }}" class="action-button" style="background: #f59e0b;">Submit Report</a>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        If you need assistance or have questions about the reporting requirements, please contact our office.
    </p>
@endsection
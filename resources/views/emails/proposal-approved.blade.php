@extends('emails.layouts.master')

@section('title', 'Proposal Approved - ARG Portal')

@section('content')
    <div class="greeting">Congratulations {{ $user->name }}!</div>
    
    <div class="message">
        <p>We are pleased to inform you that your research proposal <strong>"{{ $proposal->title }}"</strong> has been approved for funding.</p>
    </div>
    
    <div class="info-box" style="border-left-color: #10b981; background: #f0fdf4;">
        <p><strong>Approval Details:</strong></p>
        <p>Proposal: {{ $proposal->title }}</p>
        <p>Approved Amount: KES {{ number_format($proposal->approved_amount ?? 0) }}</p>
        <p>Project Duration: {{ $proposal->duration }} months</p>
        <p>Approval Date: {{ now()->format('F j, Y') }}</p>
    </div>
    
    <div class="message">
        <p>Your research project can now commence. Please review the terms and conditions of the grant and begin your research activities as outlined in your proposal.</p>
        
        <p><strong>Next Steps:</strong></p>
        <ul style="margin-left: 20px; margin-top: 10px;">
            <li>Review grant terms and conditions</li>
            <li>Submit progress reports as scheduled</li>
            <li>Maintain proper financial records</li>
            <li>Acknowledge the University in publications</li>
        </ul>
    </div>
    
    <a href="{{ url('/projects/' . $proposal->id) }}" class="action-button" style="background: #10b981;">View Project</a>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        We look forward to the successful completion of your research project. Best wishes for your research endeavors!
    </p>
@endsection
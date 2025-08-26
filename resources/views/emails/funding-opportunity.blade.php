@extends('emails.layouts.master')

@section('title', 'New Funding Opportunity - ARG Portal')

@section('content')
    <div class="greeting">Dear {{ $user->name }},</div>
    
    <div class="message">
        <p>A new funding opportunity has been announced that matches your research interests.</p>
    </div>
    
    <div class="info-box">
        <p><strong>{{ $grant->title }}</strong></p>
        <p>Maximum Award: KES {{ number_format($grant->max_amount) }}</p>
        <p>Application Deadline: {{ $grant->deadline->format('F j, Y') }}</p>
        <p>Duration: {{ $grant->duration }} months</p>
    </div>
    
    <div class="message">
        <p>{{ $grant->description }}</p>
    </div>
    
    <a href="{{ url('/grants/' . $grant->id) }}" class="action-button">View Details & Apply</a>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        Don't miss this opportunity to advance your research. Applications are reviewed on a competitive basis.
    </p>
@endsection
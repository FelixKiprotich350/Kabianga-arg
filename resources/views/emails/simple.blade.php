@extends('emails.layouts.master')

@section('title', $subject ?? 'University of Kabianga - ARG Portal')

@section('content')
    <div class="message">
        <p>{{ $content }}</p>
    </div>
    
    @if(isset($actionUrl) && $actionUrl)
        <a href="{{ $actionUrl }}" class="action-button">{{ $actionText ?? 'Click Here' }}</a>
    @endif
@endsection
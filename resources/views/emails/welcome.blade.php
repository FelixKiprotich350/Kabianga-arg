@extends('emails.layouts.master')

@section('title', 'Welcome to ARG Portal')

@section('content')
    <div class="greeting">Welcome {{ $user->name }}!</div>
    
    <div class="message">
        <p>Your account has been successfully created for the University of Kabianga Annual Research Grants Portal. You can now access all the features available to researchers.</p>
    </div>
    
    <div class="info-box">
        <p><strong>Your Account Details:</strong></p>
        <p>Name: {{ $user->name }}</p>
        <p>Email: {{ $user->email }}</p>
        <p>Department: {{ $user->department->name ?? 'Not specified' }}</p>
        <p>Account Type: {{ ucfirst($user->user_type) }}</p>
    </div>
    
    <div class="message">
        <p><strong>What you can do:</strong></p>
        <ul style="margin-left: 20px; margin-top: 10px;">
            <li>Submit research proposals for funding</li>
            <li>Track your proposal status and reviews</li>
            <li>Manage active research projects</li>
            <li>Submit progress reports and updates</li>
            <li>Access funding opportunities and guidelines</li>
            <li>Collaborate with other researchers</li>
        </ul>
    </div>
    
    <a href="{{ url('/dashboard') }}" class="action-button">Access Your Dashboard</a>
    
    <div class="divider"></div>
    
    <div class="message">
        <p><strong>Getting Started:</strong></p>
        <p>1. Complete your profile information<br>
        2. Review available funding opportunities<br>
        3. Explore the proposal submission guidelines<br>
        4. Contact us if you need any assistance</p>
    </div>
    
    <p style="font-size: 14px; color: #6b7280;">
        We're excited to support your research journey. Welcome to the University of Kabianga research community!
    </p>
@endsection
@extends('emails.layouts.master')

@section('title', 'Proposal Changes Requested - ARG Portal')

@section('content')
    <div class="greeting">Dear {{ $user->name }},</div>
    
    <div class="message">
        <p>Your research proposal <strong>"{{ $proposal->title }}"</strong> has been reviewed. The evaluation committee has requested some changes before final approval.</p>
    </div>
    
    <div class="info-box" style="border-left-color: #f59e0b; background: #fffbeb;">
        <p><strong>Action Required:</strong></p>
        <p>Please review the feedback below and make the necessary revisions to your proposal.</p>
    </div>
    
    @if(isset($changes) && $changes)
    <div class="message">
        <p><strong>Requested Changes:</strong></p>
        <div style="background: #f9fafb; padding: 15px; border-radius: 4px; margin: 15px 0;">
            {!! nl2br(e($changes)) !!}
        </div>
    </div>
    @endif
    
    <div class="message">
        <p>You have <strong>{{ $deadline ?? '14 days' }}</strong> to submit your revised proposal. Please address all the points mentioned above.</p>
    </div>
    
    <a href="{{ url('/proposals/' . $proposal->id . '/edit') }}" class="action-button" style="background: #f59e0b;">Revise Proposal</a>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        If you have questions about the requested changes, please contact the Research Office for clarification.
    </p>
@endsection
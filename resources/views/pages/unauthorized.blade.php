@extends('layouts.app')

@section('title', 'Access Denied - UoK ARG Portal')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 60vh;">
    <div class="text-center">
        <div class="mb-4">
            <i class="bi bi-shield-exclamation display-1 text-warning"></i>
        </div>
        <h2 class="mb-3">Access Denied</h2>
        <div class="alert alert-warning mx-auto" style="max-width: 500px;">
            @if (isset($message))
                {{ $message }}
            @else
                You don't have permission to access this page.
            @endif
        </div>
        <div class="mt-4">
            <a href="{{ route('pages.home') }}" class="btn btn-primary me-2">
                <i class="bi bi-house me-2"></i>Go Home
            </a>
            <button onclick="history.back()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Go Back
            </button>
        </div>
    </div>
</div>
@endsection
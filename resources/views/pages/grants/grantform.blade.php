@extends('layouts.app')

@section('title', 'Grant Details - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ $isreadonlypage ? 'View' : 'Edit' }} Grant</h2>
            <p class="text-muted mb-0">{{ $grant->title }}</p>
        </div>
        <a href="{{ route('pages.grants.home') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Grants
        </a>
    </div>

    <div class="form-card">
        <form id="grantForm" method="POST" action="{{ route('api.grants.updategrant', $grant->grantid) }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Grant ID</label>
                        <input type="text" class="form-control" value="{{ $grant->grantid }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" {{ $isreadonlypage ? 'disabled' : '' }}>
                            <option value="Open" {{ $grant->status == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Closed" {{ $grant->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Grant Title *</label>
                <input type="text" class="form-control" name="title" 
                       value="{{ $grant->title }}" {{ $isreadonlypage ? 'readonly' : '' }} required>
            </div>

            <div class="mb-3">
                <label class="form-label">Financial Year</label>
                <input type="text" class="form-control" name="finyear" 
                       value="{{ $grant->financialyear->finyear ?? 'N/A' }}" {{ $isreadonlypage ? 'readonly' : '' }}>
            </div>

            <div class="mb-3">
                <label class="form-label">Created Date</label>
                <input type="text" class="form-control" value="{{ $grant->created_at->format('M d, Y H:i') }}" readonly>
            </div>

            @if(!$isreadonlypage)
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Grant</button>
                <a href="{{ route('pages.grants.home') }}" class="btn btn-secondary">Cancel</a>
            </div>
            @endif
        </form>
    </div>

    <!-- Grant Statistics -->
    <div class="form-card mt-4">
        <h5 class="mb-3">Grant Statistics</h5>
        <div class="row">
            <div class="col-md-4">
                <div class="text-center">
                    <h3 class="text-primary">{{ $grant->proposals->count() }}</h3>
                    <p class="text-muted">Total Proposals</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h3 class="text-success">{{ $grant->proposals->where('status', 'Approved')->count() }}</h3>
                    <p class="text-muted">Approved Proposals</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h3 class="text-warning">{{ $grant->proposals->where('status', 'Pending')->count() }}</h3>
                    <p class="text-muted">Pending Proposals</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#grantForm').on('submit', function(e) {
    e.preventDefault();
    $.post($(this).attr('action'), $(this).serialize())
        .done(() => {
            ARGPortal.showSuccess('Grant updated successfully');
            setTimeout(() => window.location.href = "{{ route('pages.grants.home') }}", 1500);
        })
        .fail(() => ARGPortal.showError('Failed to update grant'));
});
</script>
@endpush
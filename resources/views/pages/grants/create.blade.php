@extends('layouts.app')

@section('title', 'Create Grant - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Create New Grant</h2>
            <p class="text-muted mb-0">Add a new research grant</p>
        </div>
        <a href="{{ route('pages.grants.home') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Grants
        </a>
    </div>

    <div class="form-card">
        <form id="createGrantForm" method="POST" action="{{ route('api.grants.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Grant Title *</label>
                <input type="text" class="form-control" name="title" placeholder="Enter grant title" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Financial Year *</label>
                        <select class="form-select" name="finyearfk" required>
                            <option value="">Select Financial Year</option>
                            @if(isset($finyears))
                                @foreach($finyears as $year)
                                <option value="{{ $year->id }}">{{ $year->finyear }} ({{ $year->startdate }} - {{ $year->enddate }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select class="form-select" name="status" required>
                            <option value="Open">Open</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Create Grant</button>
                <a href="{{ route('pages.grants.home') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#createGrantForm').on('submit', function(e) {
    e.preventDefault();
    $.post($(this).attr('action'), $(this).serialize())
        .done(() => {
            ARGPortal.showSuccess('Grant created successfully');
            setTimeout(() => window.location.href = "{{ route('pages.grants.home') }}", 1500);
        })
        .fail(() => ARGPortal.showError('Failed to create grant'));
});
</script>
@endpush
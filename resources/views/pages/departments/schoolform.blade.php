@extends('layouts.app')

@section('title', 'School Details - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ $isreadonlypage ? 'View' : 'Edit' }} School</h2>
            <p class="text-muted mb-0">{{ $school->schoolname }}</p>
        </div>
        <a href="{{ route('pages.schools.home') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Schools
        </a>
    </div>

    <div class="form-card">
        <form id="schoolForm" method="POST" action="{{ route('api.schools.updateschool', $school->schoolid) }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">School Name *</label>
                        <input type="text" class="form-control" name="schoolname" 
                               value="{{ $school->schoolname }}" {{ $isreadonlypage ? 'readonly' : '' }} required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Departments</label>
                        <div class="badge bg-primary">{{ $school->departments->count() }} departments</div>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3" 
                          {{ $isreadonlypage ? 'readonly' : '' }}>{{ $school->description }}</textarea>
            </div>

            @if(!$isreadonlypage)
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update School</button>
                <a href="{{ route('pages.schools.home') }}" class="btn btn-secondary">Cancel</a>
            </div>
            @endif
        </form>
    </div>

    @if($school->departments->count() > 0)
    <div class="form-card mt-4">
        <h5 class="mb-3">Departments in {{ $school->schoolname }}</h5>
        <div class="row">
            @foreach($school->departments as $dept)
            <div class="col-md-6 mb-3">
                <div class="border rounded p-3">
                    <h6 class="mb-1">{{ $dept->shortname }}</h6>
                    <p class="text-muted small mb-2">{{ $dept->description ?: 'No description' }}</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pages.departments.viewdepartment', $dept->depid) }}" class="btn btn-sm btn-outline-primary">View</a>
                        <a href="{{ route('pages.departments.editdepartment', $dept->depid) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$('#schoolForm').on('submit', function(e) {
    e.preventDefault();
    $.post($(this).attr('action'), $(this).serialize())
        .done(() => {
            ARGPortal.showSuccess('School updated successfully');
            setTimeout(() => window.location.href = "{{ route('pages.schools.home') }}", 1500);
        })
        .fail(() => ARGPortal.showError('Failed to update school'));
});
</script>
@endpush
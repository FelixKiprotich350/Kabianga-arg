@extends('layouts.app')

@section('title', 'Edit Department - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Department</h2>
            <p class="text-muted mb-0">{{ $department->shortname }}</p>
        </div>
        <a href="{{ route('pages.schools.home') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Schools
        </a>
    </div>

    <div class="form-card">
        <form id="departmentForm" method="POST" action="{{ route('api.departments.updatedepartment', $department->depid) }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Department Name *</label>
                        <input type="text" class="form-control" name="shortname" 
                               value="{{ $department->shortname }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">School *</label>
                        <select class="form-select" name="schoolfk" required>
                            @foreach($schools as $school)
                            <option value="{{ $school->schoolid }}" 
                                    {{ $department->schoolfk == $school->schoolid ? 'selected' : '' }}>
                                {{ $school->schoolname }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3">{{ $department->description }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Department</button>
                <a href="{{ route('pages.schools.home') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#departmentForm').on('submit', function(e) {
    e.preventDefault();
    $.post($(this).attr('action'), $(this).serialize())
        .done(() => {
            ARGPortal.showSuccess('Department updated successfully');
            setTimeout(() => window.location.href = "{{ route('pages.schools.home') }}", 1500);
        })
        .fail(() => ARGPortal.showError('Failed to update department'));
});
</script>
@endpush
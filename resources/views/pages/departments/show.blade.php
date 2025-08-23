@extends('layouts.app')

@section('title', 'Department Details - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Department Details</h2>
            <p class="text-muted mb-0">{{ $department->shortname }}</p>
        </div>
        <a href="{{ route('pages.schools.home') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Schools
        </a>
    </div>

    <div class="form-card">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Department Name</label>
                    <input type="text" class="form-control" value="{{ $department->shortname }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">School</label>
                    <input type="text" class="form-control" value="{{ $department->school->schoolname }}" readonly>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="3" readonly>{{ $department->description ?: 'No description provided' }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('pages.departments.editdepartment', $department->depid) }}" class="btn btn-primary">Edit Department</a>
            <a href="{{ route('pages.schools.home') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
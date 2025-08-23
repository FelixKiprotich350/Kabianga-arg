@extends('layouts.app')

@section('title', 'Departments - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">All Departments</h2>
            <p class="text-muted mb-0">Manage university departments</p>
        </div>
        <a href="{{ route('pages.schools.home') }}" class="btn btn-primary">
            <i class="bi bi-building me-2"></i>Schools & Departments
        </a>
    </div>

    <div class="form-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>School</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alldepartments as $dept)
                    <tr>
                        <td>{{ $dept->shortname }}</td>
                        <td>{{ $dept->school->schoolname ?? 'N/A' }}</td>
                        <td>{{ $dept->description ?: 'No description' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('pages.departments.viewdepartment', $dept->depid) }}" 
                                   class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('pages.departments.editdepartment', $dept->depid) }}" 
                                   class="btn btn-sm btn-outline-secondary">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
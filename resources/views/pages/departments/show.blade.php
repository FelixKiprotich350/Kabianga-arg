@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Department Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('pages.departments.editdepartment', $department->depid) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Department Name:</strong>
                            <p>{{ $department->shortname }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>School:</strong>
                            <p>{{ $department->school->shortname ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <strong>Description:</strong>
                            <p>{{ $department->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Reports - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Reports & Analytics</h2>
            <p class="text-muted mb-0">View comprehensive reports and analysis</p>
        </div>
    </div>

    <div class="row" id="reports-content">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Loading reports...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    PageLoaders.loadReportsData();
});
</script>
@endpush
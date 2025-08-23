@extends('layouts.app')

@section('title', 'My Applications - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">My Applications</h2>
            <p class="text-muted mb-0">Track and manage your research proposals</p>
        </div>
        <a href="{{ route('pages.proposals.viewnewproposal') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>New Application
        </a>
    </div>

    <!-- Filter Section -->
    <div class="form-card mb-4">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label for="statusFilter" class="form-label fw-medium">Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="themeFilter" class="form-label fw-medium">Theme</label>
                <select class="form-select" id="themeFilter">
                    <option value="">All Themes</option>
                    @foreach($themes ?? [] as $theme)
                        <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="searchInput" class="form-label fw-medium">Search</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search by title or ID...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" id="clearFilters">
                    <i class="bi bi-x-circle me-2"></i>Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover" id="applicationsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Theme</th>
                        <th>Grant</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="applicationsTableBody">
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
        
        <!-- Loading State -->
        <div id="loadingState" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading applications...</p>
        </div>
        
        <!-- Empty State -->
        <div id="emptyState" class="text-center py-5" style="display: none;">
            <i class="bi bi-file-text display-1 text-muted mb-3"></i>
            <h5>No Applications Found</h5>
            <p class="text-muted">You haven't submitted any applications yet.</p>
            <a href="{{ route('pages.proposals.viewnewproposal') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Submit Your First Application
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    PageLoaders.loadProposalsData('my');
});
    
</script>
@endpush
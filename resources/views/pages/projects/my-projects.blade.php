@extends('layouts.app')

@section('title', 'My Projects - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">My Projects</h2>
            <p class="text-muted mb-0">Track and manage your research projects</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="bi bi-kanban"></i>
                </div>
                <div class="stats-number" id="totalProjects">0</div>
                <div class="stats-label">Total Projects</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="bi bi-play-circle"></i>
                </div>
                <div class="stats-number" id="activeProjects">0</div>
                <div class="stats-label">Active</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="bi bi-pause-circle"></i>
                </div>
                <div class="stats-number" id="pausedProjects">0</div>
                <div class="stats-label">Paused</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-number" id="completedProjects">0</div>
                <div class="stats-label">Completed</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="form-card mb-4">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-medium">Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="paused">Paused</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Search</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search projects...">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100" id="clearFilters">
                    <i class="bi bi-x-circle me-2"></i>Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Projects Grid -->
    <div class="row" id="projectsGrid">
        <!-- Projects loaded via AJAX -->
    </div>
    
    <div id="loadingState" class="text-center py-5">
        <div class="spinner-border text-primary"></div>
        <p class="mt-2 text-muted">Loading projects...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    PageLoaders.loadProjectsData('my');
});
    
</script>
@endpush
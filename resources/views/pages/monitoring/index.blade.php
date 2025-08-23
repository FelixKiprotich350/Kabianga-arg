@extends('layouts.app')

@section('title', 'Project Monitoring - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Project Monitoring</h2>
            <p class="text-muted mb-0">Monitor and evaluate research project progress</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="bi bi-eye"></i>
                </div>
                <div class="stats-number" id="totalProjects">0</div>
                <div class="stats-label">Projects Monitored</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-number" id="onTrackProjects">0</div>
                <div class="stats-label">On Track</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stats-number" id="atRiskProjects">0</div>
                <div class="stats-label">At Risk</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="bi bi-file-text"></i>
                </div>
                <div class="stats-number" id="totalReports">0</div>
                <div class="stats-label">Monitoring Reports</div>
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
    PageLoaders.loadMonitoringData();
});
    
</script>
@endpush
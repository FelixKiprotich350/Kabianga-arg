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
$(document).ready(function() {
    let currentData = [];
    
    loadProjects();
    
    function loadProjects() {
        $('#loadingState').show();
        
        $.ajax({
            url: "{{ route('api.projects.fetchallprojects') }}",
            type: 'GET',
            success: function(response) {
                currentData = response.data || response || [];
                displayProjects(currentData);
                updateStats();
            },
            error: function() {
                ARGPortal.showError('Failed to load projects');
                $('#loadingState').hide();
            }
        });
    }
    
    function displayProjects(data) {
        $('#loadingState').hide();
        const grid = $('#projectsGrid');
        grid.empty();
        
        if (data.length === 0) {
            grid.append(`
                <div class="col-12 text-center py-5">
                    <i class="bi bi-eye display-1 text-muted"></i>
                    <h5 class="mt-3">No Projects to Monitor</h5>
                    <p class="text-muted">Active projects will appear here for monitoring.</p>
                </div>
            `);
            return;
        }
        
        data.forEach(function(project) {
            const progress = project.progress || 0;
            const riskLevel = progress < 30 ? 'danger' : progress < 70 ? 'warning' : 'success';
            
            grid.append(`
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stats-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="stats-icon primary">
                                <i class="bi bi-eye"></i>
                            </div>
                            <span class="badge bg-${riskLevel}">${riskLevel === 'success' ? 'On Track' : riskLevel === 'warning' ? 'At Risk' : 'Critical'}</span>
                        </div>
                        <h6 class="fw-bold mb-2">${project.researchnumber || 'N/A'}</h6>
                        <p class="text-muted small mb-3">${project.proposal?.researchtitle || 'Untitled Project'}</p>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Progress</span>
                                <span>${progress}%</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-${riskLevel}" style="width: ${progress}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="stats-icon success me-2" style="width: 20px; height: 20px; font-size: 0.7rem;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <small>${project.applicant?.name || 'N/A'}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="stats-icon info me-2" style="width: 20px; height: 20px; font-size: 0.7rem;">
                                    <i class="bi bi-building"></i>
                                </div>
                                <small>${project.proposal?.department?.shortname || 'N/A'}</small>
                            </div>
                        </div>
                        
                        <div class="mt-auto">
                            <div class="d-flex gap-1">
                                <a href="{{ route('pages.supervision.monitoring.monitoringpage', '') }}/${project.researchid}" 
                                   class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="bi bi-eye me-1"></i>Monitor
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        });
    }
    
    function updateStats() {
        const total = currentData.length;
        const onTrack = currentData.filter(p => (p.progress || 0) >= 70).length;
        const atRisk = currentData.filter(p => (p.progress || 0) < 70 && (p.progress || 0) >= 30).length;
        
        $('#totalProjects').text(total);
        $('#onTrackProjects').text(onTrack);
        $('#atRiskProjects').text(atRisk);
        $('#totalReports').text(total * 2);
    }
});
</script>
@endpush
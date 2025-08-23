@extends('layouts.app')

@section('title', 'Monitor Project - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Monitor Project</h2>
            <p class="text-muted mb-0">{{ $project->researchnumber ?? 'Project Monitoring' }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#reportModal">
                <i class="bi bi-plus-circle me-2"></i>Add Report
            </button>
            <a href="{{ route('pages.monitoring.home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <!-- Project Overview -->
            <div class="form-card mb-4">
                <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Project Overview</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Project Number</label>
                        <p class="fw-medium">{{ $project->researchnumber }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Status</label>
                        <p class="fw-medium">
                            <span class="badge {{ $project->projectstatus == 'Active' ? 'bg-success' : 'bg-warning' }}">
                                {{ $project->projectstatus }}
                            </span>
                        </p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Research Title</label>
                        <p class="fw-medium">{{ $project->proposal->researchtitle ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Monitoring Reports -->
            <div class="form-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Monitoring Reports</h5>
                    <span class="badge bg-primary" id="reportCount">0</span>
                </div>
                <div id="monitoringReports">
                    <!-- Loaded via AJAX -->
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Researcher Info -->
            <div class="form-card mb-4">
                <h6 class="mb-3"><i class="bi bi-person me-2"></i>Principal Investigator</h6>
                <div class="text-center mb-3">
                    <div class="stats-icon primary mx-auto mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="bi bi-person"></i>
                    </div>
                    <h6>{{ $project->applicant->name ?? 'N/A' }}</h6>
                    <p class="text-muted small">{{ $project->applicant->email ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Monitoring Stats -->
            <div class="form-card">
                <h6 class="mb-3"><i class="bi bi-bar-chart me-2"></i>Monitoring Summary</h6>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stats-number text-primary" id="totalReports">0</div>
                        <div class="stats-label">Reports</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stats-number text-success" id="lastReportDays">0</div>
                        <div class="stats-label">Days Ago</div>
                    </div>
                    <div class="col-6">
                        <div class="stats-number text-warning" id="riskLevel">Low</div>
                        <div class="stats-label">Risk Level</div>
                    </div>
                    <div class="col-6">
                        <div class="stats-number text-info" id="overallScore">85</div>
                        <div class="stats-label">Score</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Monitoring Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="reportForm" class="ajax-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Monitoring Report *</label>
                        <textarea class="form-control" name="report" rows="6" placeholder="Enter monitoring observations and recommendations..." required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Risk Level</label>
                            <select class="form-select" name="risk_level">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Overall Score (1-100)</label>
                            <input type="number" class="form-control" name="score" min="1" max="100" value="85">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    loadMonitoringReports();
    
    $('#reportForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('api.supervision.monitoring.addreport', $project->researchid) }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                ARGPortal.showSuccess('Monitoring report submitted');
                $('#reportModal').modal('hide');
                loadMonitoringReports();
            },
            error: function() {
                ARGPortal.showError('Failed to submit report');
            }
        });
    });
    
    function loadMonitoringReports() {
        $.ajax({
            url: "{{ route('api.supervision.monitoring.fetchmonitoringreport', $project->researchid) }}",
            type: 'GET',
            success: function(response) {
                const container = $('#monitoringReports');
                $('#reportCount').text(response.length);
                $('#totalReports').text(response.length);
                
                if (response.length === 0) {
                    container.html('<p class="text-muted">No monitoring reports submitted yet</p>');
                    return;
                }
                
                container.empty();
                response.forEach(function(report, index) {
                    const riskBadge = getRiskBadge(report.risk_level || 'low');
                    
                    container.append(`
                        <div class="border-start border-success ps-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-1">Monitoring Report #${index + 1}</h6>
                                <div class="d-flex gap-2">
                                    ${riskBadge}
                                    <span class="badge bg-info">${report.score || 85}/100</span>
                                </div>
                            </div>
                            <p class="mb-2">${report.report}</p>
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>${new Date(report.created_at).toLocaleDateString()}
                                <i class="bi bi-person ms-3 me-1"></i>Monitoring Team
                            </small>
                        </div>
                    `);
                });
                
                // Update stats
                if (response.length > 0) {
                    const lastReport = response[response.length - 1];
                    const daysSince = Math.floor((new Date() - new Date(lastReport.created_at)) / (1000 * 60 * 60 * 24));
                    $('#lastReportDays').text(daysSince);
                    $('#riskLevel').text(lastReport.risk_level || 'Low');
                    $('#overallScore').text(lastReport.score || 85);
                }
            }
        });
    }
    
    function getRiskBadge(level) {
        const badges = {
            'low': '<span class="badge bg-success">Low Risk</span>',
            'medium': '<span class="badge bg-warning">Medium Risk</span>',
            'high': '<span class="badge bg-danger">High Risk</span>'
        };
        return badges[level] || badges['low'];
    }
});
</script>
@endpush
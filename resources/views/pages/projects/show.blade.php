@extends('layouts.app')

@section('title', 'Project Details - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ $project->researchnumber ?? 'Project Details' }}</h2>
            <p class="text-muted mb-0">{{ $project->proposal->researchtitle ?? 'Research Project' }}</p>
        </div>
        <div class="d-flex gap-2">
            @if(Auth::user()->userid == $project->applicant->userid)
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#progressModal">
                <i class="bi bi-plus-circle me-2"></i>Add Progress
            </button>
            @endif
            <a href="{{ route('pages.projects.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <!-- Project Overview -->
            <div class="form-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Project Overview</h5>
                    <span class="badge {{ $project->projectstatus == \App\Models\ResearchProject::STATUS_ACTIVE ? 'bg-success' : ($project->projectstatus == \App\Models\ResearchProject::STATUS_COMPLETED ? 'bg-info' : 'bg-warning') }}">
                        {{ $project->projectstatus }}
                    </span>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Project Number</label>
                        <p class="fw-medium">{{ $project->researchnumber }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Research Title</label>
                        <p class="fw-medium">{{ $project->proposal->researchtitle ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Start Date</label>
                        <p class="fw-medium">{{ $project->proposal->commencingdate ? date('M d, Y', strtotime($project->proposal->commencingdate)) : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">End Date</label>
                        <p class="fw-medium">{{ $project->proposal->terminationdate ? date('M d, Y', strtotime($project->proposal->terminationdate)) : 'N/A' }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Objectives</label>
                        <p class="fw-medium">{{ $project->proposal->objectives ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Progress Reports -->
            <div class="form-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Progress Reports</h5>
                    <span class="badge bg-primary" id="progressCount">0</span>
                </div>
                <div id="progressReports">
                    <!-- Loaded via AJAX -->
                </div>
            </div>

            <!-- Funding History -->
            <div class="form-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Funding History</h5>
                    @if(Auth::user()->haspermission('canaddprojectfunding'))
                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#fundingModal">
                        <i class="bi bi-plus me-2"></i>Add Funding
                    </button>
                    @endif
                </div>
                <div id="fundingHistory">
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
                <div class="small">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Department:</span>
                        <span>{{ $project->proposal->department->shortname ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phone:</span>
                        <span>{{ $project->proposal->cellphone ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Qualification:</span>
                        <span>{{ $project->proposal->highqualification ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Project Stats -->
            <div class="form-card mb-4">
                <h6 class="mb-3"><i class="bi bi-bar-chart me-2"></i>Project Statistics</h6>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stats-number text-primary" id="totalFunding">0</div>
                        <div class="stats-label">Total Funding</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stats-number text-success" id="fundingTranches">0</div>
                        <div class="stats-label">Tranches</div>
                    </div>
                    <div class="col-6">
                        <div class="stats-number text-warning" id="progressReportsCount">0</div>
                        <div class="stats-label">Reports</div>
                    </div>
                    <div class="col-6">
                        <div class="stats-number text-info" id="projectDuration">0</div>
                        <div class="stats-label">Months</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if(Auth::user()->haspermission('canpauseresearchproject') || Auth::user()->haspermission('cancompleteresearchproject'))
            <div class="form-card">
                <h6 class="mb-3"><i class="bi bi-gear me-2"></i>Project Actions</h6>
                <div class="d-grid gap-2">
                    @if($project->projectstatus == \App\Models\ResearchProject::STATUS_ACTIVE && !$project->ispaused)
                    <button class="btn btn-warning" onclick="pauseProject()">
                        <i class="bi bi-pause-circle me-2"></i>Pause Project
                    </button>
                    @endif
                    
                    @if($project->ispaused)
                    <button class="btn btn-success" onclick="resumeProject()">
                        <i class="bi bi-play-circle me-2"></i>Resume Project
                    </button>
                    @endif
                    
                    @if($project->projectstatus == \App\Models\ResearchProject::STATUS_ACTIVE)
                    <button class="btn btn-info" onclick="completeProject()">
                        <i class="bi bi-check-circle me-2"></i>Mark Complete
                    </button>
                    <button class="btn btn-danger" onclick="cancelProject()">
                        <i class="bi bi-x-circle me-2"></i>Cancel Project
                    </button>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Progress Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="progressForm" class="ajax-form">
                @csrf
                <input type="hidden" name="researchidfk" value="{{ $project->researchid }}">
                <input type="hidden" name="reportedbyfk" value="{{ Auth::user()->userid }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Progress Report *</label>
                        <textarea class="form-control" name="report" rows="6" placeholder="Describe your research progress..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Funding Modal -->
<div class="modal fade" id="fundingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Funding</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="fundingForm" class="ajax-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amount (KES) *</label>
                        <input type="number" class="form-control" name="amount" step="0.01" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Funding</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    loadProgressReports();
    loadFundingHistory();
    calculateStats();
    
    $('#progressForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('api.projects.submitmyprogress', $project->researchid) }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                ARGPortal.showSuccess('Progress report submitted');
                $('#progressModal').modal('hide');
                loadProgressReports();
            },
            error: function() {
                ARGPortal.showError('Failed to submit progress');
            }
        });
    });
    
    $('#fundingForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('api.projects.addfunding', $project->researchid) }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                ARGPortal.showSuccess('Funding added successfully');
                $('#fundingModal').modal('hide');
                loadFundingHistory();
            },
            error: function() {
                ARGPortal.showError('Failed to add funding');
            }
        });
    });
    
    function loadProgressReports() {
        $.ajax({
            url: "{{ route('api.projects.fetchprojectprogress', $project->researchid) }}",
            type: 'GET',
            success: function(response) {
                const container = $('#progressReports');
                $('#progressCount').text(response.length);
                
                if (response.length === 0) {
                    container.html('<p class="text-muted">No progress reports submitted yet</p>');
                    return;
                }
                
                container.empty();
                response.forEach(function(report, index) {
                    container.append(`
                        <div class="border-start border-primary ps-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Progress Report #${index + 1}</h6>
                                <small class="text-muted">${new Date(report.created_at).toLocaleDateString()}</small>
                            </div>
                            <p class="mb-0">${report.report}</p>
                        </div>
                    `);
                });
            }
        });
    }
    
    function loadFundingHistory() {
        $.ajax({
            url: "{{ route('api.projects.fetchprojectfunding', $project->researchid) }}",
            type: 'GET',
            success: function(response) {
                const container = $('#fundingHistory');
                
                if (response.fundingrows === 0) {
                    container.html('<p class="text-muted">No funding records available</p>');
                    return;
                }
                
                $('#totalFunding').text('KES ' + ARGPortal.formatNumber(response.total));
                $('#fundingTranches').text(response.fundingrows);
                
                container.empty();
                response.fundingrecords.forEach(function(funding, index) {
                    container.append(`
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <div class="fw-medium">Tranche ${index + 1}</div>
                                <small class="text-muted">${new Date(funding.created_at).toLocaleDateString()}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-medium">KES ${ARGPortal.formatNumber(funding.amount)}</div>
                                <small class="text-muted">by ${funding.applicant?.name || 'System'}</small>
                            </div>
                        </div>
                    `);
                });
            }
        });
    }
    
    function calculateStats() {
        const startDate = new Date('{{ $project->proposal->commencingdate }}');
        const endDate = new Date('{{ $project->proposal->terminationdate }}');
        const duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24 * 30));
        $('#projectDuration').text(duration > 0 ? duration : 'N/A');
    }
    
    window.pauseProject = function() {
        if (confirm('Pause this project?')) {
            $.post("{{ route('api.projects.pauseproject', $project->researchid) }}", {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function() {
                ARGPortal.showSuccess('Project paused');
                setTimeout(() => location.reload(), 1500);
            });
        }
    };
    
    window.resumeProject = function() {
        if (confirm('Resume this project?')) {
            $.post("{{ route('api.projects.resumeproject', $project->researchid) }}", {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function() {
                ARGPortal.showSuccess('Project resumed');
                setTimeout(() => location.reload(), 1500);
            });
        }
    };
    
    window.completeProject = function() {
        if (confirm('Mark this project as complete?')) {
            $.post("{{ route('api.projects.completeproject', $project->researchid) }}", {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function() {
                ARGPortal.showSuccess('Project completed');
                setTimeout(() => location.reload(), 1500);
            });
        }
    };
    
    window.cancelProject = function() {
        if (confirm('Cancel this project? This action cannot be undone.')) {
            $.post("{{ route('api.projects.cancelproject', $project->researchid) }}", {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function() {
                ARGPortal.showSuccess('Project cancelled');
                setTimeout(() => location.reload(), 1500);
            });
        }
    };
});
</script>
@endpush
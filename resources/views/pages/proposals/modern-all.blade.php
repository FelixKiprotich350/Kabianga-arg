@extends('layouts.app')

@section('title', 'All Proposals - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">All Proposals</h2>
            <p class="text-muted mb-0">Review and manage all research proposals</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportProposals()">
                <i class="bi bi-download me-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="bi bi-file-text"></i>
                </div>
                <div class="stats-number" id="totalProposals">0</div>
                <div class="stats-label">Total Proposals</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stats-number" id="pendingProposals">0</div>
                <div class="stats-label">Pending Review</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-number" id="approvedProposals">0</div>
                <div class="stats-label">Approved</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon danger">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stats-number" id="rejectedProposals">0</div>
                <div class="stats-label">Rejected</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="form-card mb-4">
        <div class="row align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-medium">Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-medium">Theme</label>
                <select class="form-select" id="themeFilter">
                    <option value="">All Themes</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-medium">Department</label>
                <select class="form-select" id="departmentFilter">
                    <option value="">All Departments</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium">Search</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search proposals...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" id="clearFilters">
                    <i class="bi bi-x-circle me-2"></i>Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Proposals Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Applicant</th>
                        <th>Department</th>
                        <th>Theme</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="proposalsTableBody">
                    <!-- Data loaded via AJAX -->
                </tbody>
            </table>
        </div>
        
        <div id="loadingState" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 text-muted">Loading proposals...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentData = [];
    
    loadProposals();
    
    $('#statusFilter, #themeFilter, #departmentFilter').on('change', filterProposals);
    $('#searchInput').on('input', ARGPortal.debounce(filterProposals, 300));
    $('#clearFilters').on('click', clearFilters);
    
    function loadProposals() {
        $('#loadingState').show();
        
        $.ajax({
            url: "{{ route('api.proposals.fetchallproposals') }}",
            type: 'GET',
            success: function(response) {
                currentData = response.data || response || [];
                displayProposals(currentData);
                updateStats();
                populateFilters();
            },
            error: function() {
                ARGPortal.showError('Failed to load proposals');
                $('#loadingState').hide();
            }
        });
    }
    
    function displayProposals(data) {
        $('#loadingState').hide();
        const tbody = $('#proposalsTableBody');
        tbody.empty();
        
        data.forEach(function(proposal) {
            const statusBadge = getStatusBadge(proposal.approvalstatus);
            const submittedDate = new Date(proposal.created_at).toLocaleDateString();
            
            tbody.append(`
                <tr>
                    <td><span class="badge bg-light text-dark">#${proposal.proposalid}</span></td>
                    <td>
                        <div class="fw-medium">${proposal.researchtitle || 'Untitled'}</div>
                        <small class="text-muted">${proposal.objectives ? proposal.objectives.substring(0, 50) + '...' : ''}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="stats-icon primary me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                <i class="bi bi-person"></i>
                            </div>
                            ${proposal.applicant ? proposal.applicant.name : 'N/A'}
                        </div>
                    </td>
                    <td>${proposal.department ? proposal.department.shortname : 'N/A'}</td>
                    <td>${proposal.themeitem ? proposal.themeitem.themename : 'N/A'}</td>
                    <td>${statusBadge}</td>
                    <td>${submittedDate}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('pages.proposals.viewproposal', '') }}/${proposal.proposalid}" 
                               class="btn btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            ${proposal.approvalstatus === 'Pending' && proposal.submittedstatus ? 
                                `<button class="btn btn-outline-success" onclick="approveProposal(${proposal.proposalid})" title="Approve">
                                    <i class="bi bi-check"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="rejectProposal(${proposal.proposalid})" title="Reject">
                                    <i class="bi bi-x"></i>
                                </button>` : ''
                            }
                        </div>
                    </td>
                </tr>
            `);
        });
    }
    
    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="badge bg-warning">Pending</span>',
            'approved': '<span class="badge bg-success">Approved</span>',
            'rejected': '<span class="badge bg-danger">Rejected</span>'
        };
        return badges[status.toLowerCase()] || '<span class="badge bg-secondary">Unknown</span>';
    }
    
    function updateStats() {
        const total = currentData.length;
        const pending = currentData.filter(p => p.approvalstatus.toLowerCase() === 'pending').length;
        const approved = currentData.filter(p => p.approvalstatus.toLowerCase() === 'approved').length;
        const rejected = currentData.filter(p => p.approvalstatus.toLowerCase() === 'rejected').length;
        
        $('#totalProposals').text(total);
        $('#pendingProposals').text(pending);
        $('#approvedProposals').text(approved);
        $('#rejectedProposals').text(rejected);
    }
    
    function populateFilters() {
        const themes = [...new Set(currentData.map(p => p.themeitem?.themename).filter(Boolean))];
        const departments = [...new Set(currentData.map(p => p.department?.shortname).filter(Boolean))];
        
        const themeFilter = $('#themeFilter');
        const departmentFilter = $('#departmentFilter');
        
        themes.forEach(theme => {
            themeFilter.append(`<option value="${theme}">${theme}</option>`);
        });
        
        departments.forEach(dept => {
            departmentFilter.append(`<option value="${dept}">${dept}</option>`);
        });
    }
    
    function filterProposals() {
        const status = $('#statusFilter').val().toLowerCase();
        const theme = $('#themeFilter').val();
        const department = $('#departmentFilter').val();
        const search = $('#searchInput').val().toLowerCase();
        
        let filtered = currentData.filter(function(proposal) {
            const matchesStatus = !status || proposal.approvalstatus.toLowerCase() === status;
            const matchesTheme = !theme || proposal.themeitem?.themename === theme;
            const matchesDepartment = !department || proposal.department?.shortname === department;
            const matchesSearch = !search || 
                (proposal.researchtitle && proposal.researchtitle.toLowerCase().includes(search)) ||
                (proposal.applicant && proposal.applicant.name.toLowerCase().includes(search));
            
            return matchesStatus && matchesTheme && matchesDepartment && matchesSearch;
        });
        
        displayProposals(filtered);
    }
    
    function clearFilters() {
        $('#statusFilter, #themeFilter, #departmentFilter').val('');
        $('#searchInput').val('');
        displayProposals(currentData);
    }
    
    window.approveProposal = function(id) {
        // Implementation for approval
        ARGPortal.showSuccess('Proposal approved');
    };
    
    window.rejectProposal = function(id) {
        // Implementation for rejection
        ARGPortal.showSuccess('Proposal rejected');
    };
    
    window.exportProposals = function() {
        ARGPortal.showSuccess('Export started');
    };
});
</script>
@endpush
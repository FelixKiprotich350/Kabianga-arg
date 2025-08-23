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
document.addEventListener('DOMContentLoaded', function() {
    let currentData = [];
    
    loadProposals();
    
    document.getElementById('statusFilter').addEventListener('change', filterProposals);
    document.getElementById('themeFilter').addEventListener('change', filterProposals);
    document.getElementById('departmentFilter').addEventListener('change', filterProposals);
    document.getElementById('searchInput').addEventListener('input', ARGPortal.debounce(filterProposals, 300));
    document.getElementById('clearFilters').addEventListener('click', clearFilters);
    
    async function loadProposals() {
        document.getElementById('loadingState').style.display = 'block';
        
        try {
            currentData = await API.getAllProposals();
            displayProposals(currentData);
            updateStats();
            populateFilters();
        } catch (error) {
            ARGPortal.showError('Failed to load proposals');
            document.getElementById('loadingState').style.display = 'none';
        }
    }
    
    function displayProposals(data) {
        document.getElementById('loadingState').style.display = 'none';
        const tbody = document.getElementById('proposalsTableBody');
        tbody.innerHTML = '';
        
        data.forEach(function(proposal) {
            const statusBadge = getStatusBadge(proposal.status || proposal.approvalstatus);
            const submittedDate = new Date(proposal.created_at).toLocaleDateString();
            
            tbody.innerHTML += `
                <tr>
                    <td><span class="badge bg-light text-dark">#${proposal.id || proposal.proposalid}</span></td>
                    <td>
                        <div class="fw-medium">${proposal.title || proposal.researchtitle || 'Untitled'}</div>
                        <small class="text-muted">${proposal.abstract || proposal.objectives ? (proposal.abstract || proposal.objectives).substring(0, 50) + '...' : ''}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="stats-icon primary me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                <i class="bi bi-person"></i>
                            </div>
                            ${proposal.principal_investigator || (proposal.applicant ? proposal.applicant.name : 'N/A')}
                        </div>
                    </td>
                    <td>${proposal.department_name || (proposal.department ? proposal.department.shortname : 'N/A')}</td>
                    <td>${proposal.theme_name || (proposal.themeitem ? proposal.themeitem.themename : 'N/A')}</td>
                    <td>${statusBadge}</td>
                    <td>${submittedDate}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="/proposals/view/${proposal.id || proposal.proposalid}" 
                               class="btn btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            ${(proposal.status === 'submitted' || (proposal.approvalstatus === 'Pending' && proposal.submittedstatus)) ? 
                                `<button class="btn btn-outline-success" onclick="approveProposal(${proposal.id || proposal.proposalid})" title="Approve">
                                    <i class="bi bi-check"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="rejectProposal(${proposal.id || proposal.proposalid})" title="Reject">
                                    <i class="bi bi-x"></i>
                                </button>` : ''
                            }
                        </div>
                    </td>
                </tr>
            `;
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
        const pending = currentData.filter(p => (p.status || p.approvalstatus || '').toLowerCase().includes('pending') || (p.status || p.approvalstatus || '').toLowerCase() === 'submitted').length;
        const approved = currentData.filter(p => (p.status || p.approvalstatus || '').toLowerCase() === 'approved').length;
        const rejected = currentData.filter(p => (p.status || p.approvalstatus || '').toLowerCase() === 'rejected').length;
        
        document.getElementById('totalProposals').textContent = total;
        document.getElementById('pendingProposals').textContent = pending;
        document.getElementById('approvedProposals').textContent = approved;
        document.getElementById('rejectedProposals').textContent = rejected;
    }
    
    function populateFilters() {
        const themes = [...new Set(currentData.map(p => p.theme_name || p.themeitem?.themename).filter(Boolean))];
        const departments = [...new Set(currentData.map(p => p.department_name || p.department?.shortname).filter(Boolean))];
        
        const themeFilter = document.getElementById('themeFilter');
        const departmentFilter = document.getElementById('departmentFilter');
        
        themes.forEach(theme => {
            const option = document.createElement('option');
            option.value = theme;
            option.textContent = theme;
            themeFilter.appendChild(option);
        });
        
        departments.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept;
            option.textContent = dept;
            departmentFilter.appendChild(option);
        });
    }
    
    function filterProposals() {
        const status = document.getElementById('statusFilter').value.toLowerCase();
        const theme = document.getElementById('themeFilter').value;
        const department = document.getElementById('departmentFilter').value;
        const search = document.getElementById('searchInput').value.toLowerCase();
        
        let filtered = currentData.filter(function(proposal) {
            const proposalStatus = (proposal.status || proposal.approvalstatus || '').toLowerCase();
            const matchesStatus = !status || proposalStatus === status || (status === 'pending' && proposalStatus === 'submitted');
            const matchesTheme = !theme || (proposal.theme_name || proposal.themeitem?.themename) === theme;
            const matchesDepartment = !department || (proposal.department_name || proposal.department?.shortname) === department;
            const matchesSearch = !search || 
                (proposal.title || proposal.researchtitle || '').toLowerCase().includes(search) ||
                (proposal.principal_investigator || proposal.applicant?.name || '').toLowerCase().includes(search);
            
            return matchesStatus && matchesTheme && matchesDepartment && matchesSearch;
        });
        
        displayProposals(filtered);
    }
    
    function clearFilters() {
        document.getElementById('statusFilter').value = '';
        document.getElementById('themeFilter').value = '';
        document.getElementById('departmentFilter').value = '';
        document.getElementById('searchInput').value = '';
        displayProposals(currentData);
    }
    
    window.approveProposal = async function(id) {
        if (!confirm('Are you sure you want to approve this proposal?')) return;
        try {
            await API.approveRejectProposal(id, 'approve');
            ARGPortal.showSuccess('Proposal approved successfully');
            loadProposals();
        } catch (error) {
            ARGPortal.showError('Failed to approve proposal');
        }
    };
    
    window.rejectProposal = async function(id) {
        if (!confirm('Are you sure you want to reject this proposal?')) return;
        try {
            await API.approveRejectProposal(id, 'reject');
            ARGPortal.showSuccess('Proposal rejected successfully');
            loadProposals();
        } catch (error) {
            ARGPortal.showError('Failed to reject proposal');
        }
    };
    
    window.exportProposals = function() {
        ARGPortal.showSuccess('Export functionality coming soon');
    };
});
</script>
@endpush
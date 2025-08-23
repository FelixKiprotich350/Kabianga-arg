@extends('layouts.app')

@section('title', 'All Proposals - UoK ARG Portal')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">All Proposals</h1>
            <p class="text-muted">Manage and review research proposals</p>
        </div>
        <div>
            <a href="/proposals/newproposal" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Proposal
            </a>
            <button class="btn btn-outline-secondary" onclick="PageLoaders.loadProposalsData('all')">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="search-input" class="form-control" placeholder="Search proposals...">
                        <button class="btn btn-outline-secondary" onclick="performProposalSearch()">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="status-filter" class="form-select" onchange="filterProposals()">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="submitted">Submitted</option>
                        <option value="under_review">Under Review</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="school-filter" class="form-select" onchange="filterProposals()">
                        <option value="">All Schools</option>
                        <!-- Schools will be loaded dynamically -->
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Proposals List -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Proposals List</h5>
        </div>
        <div class="card-body">
            <div id="proposals-content">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading proposals...</p>
                </div>
            </div>
            
            <div id="proposals-list"></div>
            
            <!-- Pagination -->
            <div id="proposals-pagination" class="mt-3"></div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.search-highlight {
    background-color: yellow;
    padding: 0 2px;
}

.filter-active {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.proposals-stats {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    display: block;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}
</style>
@endpush

@push('scripts')
<script>
let currentProposals = [];
let filteredProposals = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadProposalsPage();
    setupSearchDebounce();
});

async function loadProposalsPage() {
    try {
        // Load proposals and schools for filter
        const [proposals, schools] = await Promise.all([
            API.getAllProposals(),
            API.getAllSchools()
        ]);
        
        currentProposals = proposals;
        filteredProposals = proposals;
        
        populateSchoolFilter(schools);
        renderProposalsList(filteredProposals);
        renderProposalsStats(proposals);
        
    } catch (error) {
        ARGPortal.showError('Failed to load proposals data');
        console.error('Load error:', error);
    }
}

function populateSchoolFilter(schools) {
    const schoolFilter = document.getElementById('school-filter');
    schools.forEach(school => {
        const option = document.createElement('option');
        option.value = school.id;
        option.textContent = school.name;
        schoolFilter.appendChild(option);
    });
}

function renderProposalsStats(proposals) {
    const stats = {
        total: proposals.length,
        submitted: proposals.filter(p => p.status === 'submitted').length,
        approved: proposals.filter(p => p.status === 'approved').length,
        under_review: proposals.filter(p => p.status === 'under_review').length
    };
    
    const statsHtml = `
        <div class="proposals-stats">
            <div class="row">
                <div class="col-3 stat-item">
                    <span class="stat-number">${stats.total}</span>
                    <span class="stat-label">Total</span>
                </div>
                <div class="col-3 stat-item">
                    <span class="stat-number">${stats.submitted}</span>
                    <span class="stat-label">Submitted</span>
                </div>
                <div class="col-3 stat-item">
                    <span class="stat-number">${stats.under_review}</span>
                    <span class="stat-label">Under Review</span>
                </div>
                <div class="col-3 stat-item">
                    <span class="stat-number">${stats.approved}</span>
                    <span class="stat-label">Approved</span>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('proposals-content').insertAdjacentHTML('afterbegin', statsHtml);
}

function setupSearchDebounce() {
    const searchInput = document.getElementById('search-input');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performProposalSearch();
        }, 300);
    });
}

function performProposalSearch() {
    const query = document.getElementById('search-input').value.toLowerCase();
    
    if (query.trim() === '') {
        filteredProposals = currentProposals;
    } else {
        filteredProposals = currentProposals.filter(proposal => 
            proposal.title.toLowerCase().includes(query) ||
            proposal.principal_investigator.toLowerCase().includes(query) ||
            proposal.abstract.toLowerCase().includes(query)
        );
    }
    
    applyFilters();
}

function filterProposals() {
    const statusFilter = document.getElementById('status-filter').value;
    const schoolFilter = document.getElementById('school-filter').value;
    
    let filtered = filteredProposals;
    
    if (statusFilter) {
        filtered = filtered.filter(p => p.status === statusFilter);
    }
    
    if (schoolFilter) {
        filtered = filtered.filter(p => p.school_id == schoolFilter);
    }
    
    renderProposalsList(filtered);
}

function applyFilters() {
    filterProposals();
}

// Enhanced proposal list renderer
function renderProposalsList(proposals) {
    const container = document.getElementById('proposals-list');
    if (!container) return;

    if (proposals.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                <h5 class="mt-3 text-muted">No proposals found</h5>
                <p class="text-muted">Try adjusting your search or filters</p>
            </div>
        `;
        return;
    }

    container.innerHTML = `
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Principal Investigator</th>
                        <th>School</th>
                        <th>Status</th>
                        <th>Amount Requested</th>
                        <th>Submitted Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${proposals.map(proposal => `
                        <tr>
                            <td>
                                <strong>${proposal.title}</strong>
                                <br><small class="text-muted">${proposal.abstract.substring(0, 100)}...</small>
                            </td>
                            <td>${proposal.principal_investigator}</td>
                            <td>${proposal.school_name || 'N/A'}</td>
                            <td><span class="badge bg-${getStatusColor(proposal.status)}">${proposal.status.replace('_', ' ').toUpperCase()}</span></td>
                            <td>KSh ${ARGPortal.formatNumber(proposal.requested_amount)}</td>
                            <td>${new Date(proposal.created_at).toLocaleDateString()}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/proposals/view/${proposal.id}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    ${proposal.can_edit ? `
                                        <a href="/proposals/edit/${proposal.id}" class="btn btn-outline-secondary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    ` : ''}
                                    ${proposal.can_approve ? `
                                        <button class="btn btn-outline-success" onclick="approveProposal(${proposal.id})">
                                            <i class="bi bi-check"></i> Approve
                                        </button>
                                    ` : ''}
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

async function approveProposal(proposalId) {
    if (!confirm('Are you sure you want to approve this proposal?')) return;
    
    try {
        await API.approveRejectProposal(proposalId, 'approve');
        ARGPortal.showSuccess('Proposal approved successfully');
        loadProposalsPage(); // Reload data
    } catch (error) {
        ARGPortal.showError('Failed to approve proposal');
        console.error('Approve error:', error);
    }
}

// Export functions for global use
window.ProposalsPage = {
    loadProposalsPage,
    performProposalSearch,
    filterProposals,
    approveProposal
};
</script>
@endpush
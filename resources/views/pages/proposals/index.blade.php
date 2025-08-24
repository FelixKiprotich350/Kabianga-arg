@extends('layouts.app')

@section('title', 'Proposals - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Proposals</h2>
            <p class="text-muted mb-0">Manage research proposals</p>
        </div>
        <a href="{{ route('pages.proposals.viewnewproposal') }}" class="btn btn-primary">
            <i class="bi bi-plus me-2"></i>New Proposal
        </a>
    </div>

    <!-- Scope Selector -->
    <div class="form-card mb-4">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-medium">View Scope</label>
                <select class="form-select" id="scopeFilter">
                    <option value="my">My Proposals</option>
                    @if(hasAccess(['canviewallproposals', 'committee_member']))
                        <option value="all">All Proposals</option>
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-medium">Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="submitted">Submitted</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium">Search</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search proposals...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="loadProposals()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Refresh
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
                        <th>Title</th>
                        <th>Researcher</th>
                        <th>Grant</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="proposalsTableBody">
                    <!-- Data loaded via API -->
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
    loadProposals();
    
    document.getElementById('scopeFilter').addEventListener('change', loadProposals);
    document.getElementById('statusFilter').addEventListener('change', loadProposals);
    document.getElementById('searchInput').addEventListener('input', debounce(loadProposals, 300));
});

async function loadProposals() {
    const scope = document.getElementById('scopeFilter').value;
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    
    const loadingState = document.getElementById('loadingState');
    const tableBody = document.getElementById('proposalsTableBody');
    
    try {
        loadingState.style.display = 'block';
        
        const url = scope === 'my' ? '/api/v1/proposals/my' : '/api/v1/proposals';
        const params = new URLSearchParams();
        if (status) params.append('status', status);
        if (search) params.append('search', search);
        
        const response = await fetch(`${url}?${params}`);
        const result = await response.json();
        
        tableBody.innerHTML = '';
        
        if (result.success && result.data) {
            result.data.forEach(proposal => {
                const statusBadge = getStatusBadge(proposal.approvalstatus);
                const row = `
                    <tr>
                        <td>
                            <strong>${proposal.researchtitle || 'Untitled'}</strong>
                            <small class="text-muted d-block">${proposal.theme_name || ''}</small>
                        </td>
                        <td>${proposal.applicant_name || 'N/A'}</td>
                        <td>${proposal.grant_name || 'N/A'}</td>
                        <td>${statusBadge}</td>
                        <td>${proposal.created_at ? new Date(proposal.created_at).toLocaleDateString() : 'N/A'}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="viewProposal('${proposal.proposalid}')">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="editProposal('${proposal.proposalid}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No proposals found</td></tr>';
        }
    } catch (error) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Error loading proposals</td></tr>';
    } finally {
        loadingState.style.display = 'none';
    }
}

function getStatusBadge(status) {
    const badges = {
        'PENDING': '<span class="badge bg-warning">Pending</span>',
        'APPROVED': '<span class="badge bg-success">Approved</span>',
        'REJECTED': '<span class="badge bg-danger">Rejected</span>',
        'DRAFT': '<span class="badge bg-secondary">Draft</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}



function viewProposal(proposalId) {
    window.location.href = `/proposals/view/${proposalId}`;
}

function editProposal(proposalId) {
    window.location.href = `/proposals/edit/${proposalId}`;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>
@endpush
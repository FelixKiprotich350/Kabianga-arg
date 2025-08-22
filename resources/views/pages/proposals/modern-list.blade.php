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
$(document).ready(function() {
    let currentData = [];
    
    // Load applications on page load
    loadApplications();
    
    // Filter event listeners
    $('#statusFilter, #themeFilter').on('change', filterApplications);
    $('#searchInput').on('input', ARGPortal.debounce(filterApplications, 300));
    $('#clearFilters').on('click', clearFilters);
    
    function loadApplications() {
        $('#loadingState').show();
        $('#applicationsTable tbody').hide();
        $('#emptyState').hide();
        
        $.ajax({
            url: "{{ route('api.proposals.fetchmyapplications') }}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                currentData = response.data || [];
                displayApplications(currentData);
            },
            error: function(xhr, status, error) {
                console.error('Error loading applications:', error);
                ARGPortal.showError('Failed to load applications');
                $('#loadingState').hide();
                $('#emptyState').show();
            }
        });
    }
    
    function displayApplications(data) {
        $('#loadingState').hide();
        
        if (data.length === 0) {
            $('#applicationsTable tbody').hide();
            $('#emptyState').show();
            return;
        }
        
        $('#emptyState').hide();
        $('#applicationsTable tbody').show();
        
        const tbody = $('#applicationsTableBody');
        tbody.empty();
        
        data.forEach(function(app) {
            const statusBadge = getStatusBadge(app.approvalstatus);
            const submittedDate = new Date(app.created_at).toLocaleDateString();
            const amount = app.requested_amount ? 'KES ' + ARGPortal.formatNumber(app.requested_amount) : 'N/A';
            
            const row = `
                <tr>
                    <td><span class="badge bg-light text-dark">#${app.proposalid}</span></td>
                    <td>
                        <div class="fw-medium">${app.title || 'Untitled'}</div>
                        <small class="text-muted">${app.abstract ? app.abstract.substring(0, 60) + '...' : ''}</small>
                    </td>
                    <td>${app.theme_name || 'N/A'}</td>
                    <td>${app.grant_name || 'N/A'}</td>
                    <td>${amount}</td>
                    <td>${statusBadge}</td>
                    <td>${submittedDate}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('pages.proposals.viewproposal', '') }}/${app.proposalid}" 
                               class="btn btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            ${app.approvalstatus === 'pending' ? 
                                `<a href="{{ route('pages.proposals.editproposal', '') }}/${app.proposalid}" 
                                   class="btn btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>` : ''
                            }
                            <button class="btn btn-outline-info" onclick="downloadPDF(${app.proposalid})" title="Download PDF">
                                <i class="bi bi-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }
    
    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="badge bg-warning">Pending</span>',
            'approved': '<span class="badge bg-success">Approved</span>',
            'rejected': '<span class="badge bg-danger">Rejected</span>',
            'requirechange': '<span class="badge bg-info">Needs Changes</span>'
        };
        return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
    }
    
    function filterApplications() {
        const statusFilter = $('#statusFilter').val().toLowerCase();
        const themeFilter = $('#themeFilter').val();
        const searchTerm = $('#searchInput').val().toLowerCase();
        
        let filteredData = currentData.filter(function(app) {
            const matchesStatus = !statusFilter || app.approvalstatus.toLowerCase() === statusFilter;
            const matchesTheme = !themeFilter || app.themefk == themeFilter;
            const matchesSearch = !searchTerm || 
                app.title.toLowerCase().includes(searchTerm) ||
                app.proposalid.toString().includes(searchTerm);
            
            return matchesStatus && matchesTheme && matchesSearch;
        });
        
        displayApplications(filteredData);
    }
    
    function clearFilters() {
        $('#statusFilter, #themeFilter').val('');
        $('#searchInput').val('');
        displayApplications(currentData);
    }
    
    window.downloadPDF = function(proposalId) {
        window.open(`{{ route('api.proposal.printpdf', '') }}/${proposalId}`, '_blank');
    };
});
</script>
@endpush
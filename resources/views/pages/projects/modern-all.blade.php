@extends('layouts.app')

@section('title', 'All Projects - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">All Projects</h2>
            <p class="text-muted mb-0">Monitor and manage all research projects</p>
        </div>
        <button class="btn btn-outline-primary" onclick="exportProjects()">
            <i class="bi bi-download me-2"></i>Export
        </button>
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
                <div class="stats-icon info">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-number" id="completedProjects">0</div>
                <div class="stats-label">Completed</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stats-number" id="totalFunding">0</div>
                <div class="stats-label">Total Funding (M)</div>
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
                    <option value="active">Active</option>
                    <option value="paused">Paused</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-medium">Department</label>
                <select class="form-select" id="departmentFilter">
                    <option value="">All Departments</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-medium">Search</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search projects or researchers...">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100" id="clearFilters">
                    <i class="bi bi-x-circle me-2"></i>Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Researcher</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Budget</th>
                        <th>Start Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="projectsTableBody">
                    <!-- Data loaded via AJAX -->
                </tbody>
            </table>
        </div>
        
        <div id="loadingState" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 text-muted">Loading projects...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentData = [];
    
    loadProjects();
    
    $('#statusFilter, #departmentFilter').on('change', filterProjects);
    $('#searchInput').on('input', ARGPortal.debounce(filterProjects, 300));
    $('#clearFilters').on('click', clearFilters);
    
    function loadProjects() {
        $('#loadingState').show();
        
        $.ajax({
            url: "{{ route('api.projects.fetchallprojects') }}",
            type: 'GET',
            success: function(response) {
                currentData = response.data || response || [];
                displayProjects(currentData);
                updateStats();
                populateFilters();
            },
            error: function() {
                ARGPortal.showError('Failed to load projects');
                $('#loadingState').hide();
            }
        });
    }
    
    function displayProjects(data) {
        $('#loadingState').hide();
        const tbody = $('#projectsTableBody');
        tbody.empty();
        
        data.forEach(function(project) {
            const statusBadge = getStatusBadge(project.projectstatus);
            const progress = project.progress || 0;
            const startDate = project.proposal?.commencingdate ? 
                new Date(project.proposal.commencingdate).toLocaleDateString() : 'N/A';
            
            tbody.append(`
                <tr>
                    <td>
                        <div class="fw-medium">${project.researchnumber || 'N/A'}</div>
                        <small class="text-muted">${project.proposal?.researchtitle || 'Untitled'}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="stats-icon primary me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                <i class="bi bi-person"></i>
                            </div>
                            ${project.applicant?.name || 'N/A'}
                        </div>
                    </td>
                    <td>${project.proposal?.department?.shortname || 'N/A'}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="progress me-2" style="width: 60px; height: 6px;">
                                <div class="progress-bar" style="width: ${progress}%"></div>
                            </div>
                            <small>${progress}%</small>
                        </div>
                    </td>
                    <td>KES ${ARGPortal.formatNumber(project.proposal?.grantitem?.amount || 0)}</td>
                    <td>${startDate}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('pages.projects.viewanyproject', '') }}/${project.researchid}" 
                               class="btn btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            ${project.projectstatus === 'Active' ? 
                                `<button class="btn btn-outline-warning" onclick="pauseProject(${project.researchid})" title="Pause">
                                    <i class="bi bi-pause"></i>
                                </button>` : ''
                            }
                            ${project.projectstatus === 'Paused' ? 
                                `<button class="btn btn-outline-success" onclick="resumeProject(${project.researchid})" title="Resume">
                                    <i class="bi bi-play"></i>
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
            'active': '<span class="badge bg-success">Active</span>',
            'paused': '<span class="badge bg-warning">Paused</span>',
            'completed': '<span class="badge bg-info">Completed</span>',
            'cancelled': '<span class="badge bg-danger">Cancelled</span>'
        };
        return badges[status.toLowerCase()] || '<span class="badge bg-secondary">Unknown</span>';
    }
    
    function updateStats() {
        const total = currentData.length;
        const active = currentData.filter(p => p.projectstatus.toLowerCase() === 'active').length;
        const completed = currentData.filter(p => p.projectstatus.toLowerCase() === 'completed').length;
        const totalFunding = currentData.reduce((sum, p) => sum + (p.proposal?.grantitem?.amount || 0), 0);
        
        $('#totalProjects').text(total);
        $('#activeProjects').text(active);
        $('#completedProjects').text(completed);
        $('#totalFunding').text((totalFunding / 1000000).toFixed(1));
    }
    
    function populateFilters() {
        const departments = [...new Set(currentData.map(p => p.proposal?.department?.shortname).filter(Boolean))];
        const departmentFilter = $('#departmentFilter');
        
        departments.forEach(dept => {
            departmentFilter.append(`<option value="${dept}">${dept}</option>`);
        });
    }
    
    function filterProjects() {
        const status = $('#statusFilter').val().toLowerCase();
        const department = $('#departmentFilter').val();
        const search = $('#searchInput').val().toLowerCase();
        
        let filtered = currentData.filter(function(project) {
            const matchesStatus = !status || project.projectstatus.toLowerCase() === status;
            const matchesDepartment = !department || project.proposal?.department?.shortname === department;
            const matchesSearch = !search || 
                (project.proposal?.researchtitle && project.proposal.researchtitle.toLowerCase().includes(search)) ||
                (project.applicant?.name && project.applicant.name.toLowerCase().includes(search));
            
            return matchesStatus && matchesDepartment && matchesSearch;
        });
        
        displayProjects(filtered);
    }
    
    function clearFilters() {
        $('#statusFilter, #departmentFilter').val('');
        $('#searchInput').val('');
        displayProjects(currentData);
    }
    
    window.pauseProject = function(id) {
        if (confirm('Pause this project?')) {
            $.post(`{{ route('api.projects.pauseproject', '') }}/${id}`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function() {
                ARGPortal.showSuccess('Project paused');
                loadProjects();
            });
        }
    };
    
    window.resumeProject = function(id) {
        if (confirm('Resume this project?')) {
            $.post(`{{ route('api.projects.resumeproject', '') }}/${id}`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function() {
                ARGPortal.showSuccess('Project resumed');
                loadProjects();
            });
        }
    };
    
    window.exportProjects = function() {
        ARGPortal.showSuccess('Export started');
    };
});
</script>
@endpush
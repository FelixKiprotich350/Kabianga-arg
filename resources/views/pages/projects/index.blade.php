@extends('layouts.app')

@section('title', 'Projects - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Projects</h2>
            <p class="text-muted mb-0">Manage research projects</p>
        </div>
    </div>

    <!-- Scope Selector -->
    <div class="form-card mb-4">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-medium">View Scope</label>
                <select class="form-select" id="scopeFilter">
                    <option value="my">My Projects</option>
                    @if(hasAccess(['canviewallprojects', 'committee_member']))
                        <option value="all">All Projects</option>
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-medium">Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="paused">Paused</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium">Search</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search projects...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="loadProjects()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Refresh
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
                        <th>Title</th>
                        <th>Researcher</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Start Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="projectsTableBody">
                    <!-- Data loaded via API -->
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
document.addEventListener('DOMContentLoaded', function() {
    loadProjects();
    
    document.getElementById('scopeFilter').addEventListener('change', loadProjects);
    document.getElementById('statusFilter').addEventListener('change', loadProjects);
    document.getElementById('searchInput').addEventListener('input', debounce(loadProjects, 300));
});

async function loadProjects() {
    const scope = document.getElementById('scopeFilter').value;
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    
    const loadingState = document.getElementById('loadingState');
    const tableBody = document.getElementById('projectsTableBody');
    
    try {
        loadingState.style.display = 'block';
        
        const url = scope === 'my' ? '/api/v1/projects/my' : '/api/v1/projects';
        const params = new URLSearchParams();
        if (status) params.append('status', status);
        if (search) params.append('search', search);
        
        const response = await fetch(`${url}?${params}`);
        const result = await response.json();
        
        tableBody.innerHTML = '';
        
        if (result.success && result.data) {
            result.data.forEach(project => {
                const statusBadge = getStatusBadge(project.status);
                const progress = project.progress || 0;
                const row = `
                    <tr>
                        <td>
                            <strong>${project.title || 'Untitled'}</strong>
                            <small class="text-muted d-block">${project.description || ''}</small>
                        </td>
                        <td>${project.researcher_name || 'N/A'}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" role="progressbar" style="width: ${progress}%">${progress}%</div>
                            </div>
                        </td>
                        <td>${project.start_date ? new Date(project.start_date).toLocaleDateString() : 'N/A'}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="viewProject('${project.id}')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No projects found</td></tr>';
        }
    } catch (error) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Error loading projects</td></tr>';
    } finally {
        loadingState.style.display = 'none';
    }
}

function getStatusBadge(status) {
    const badges = {
        'active': '<span class="badge bg-success">Active</span>',
        'completed': '<span class="badge bg-primary">Completed</span>',
        'paused': '<span class="badge bg-warning">Paused</span>',
        'cancelled': '<span class="badge bg-danger">Cancelled</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}

function viewProject(projectId) {
    const scope = document.getElementById('scopeFilter').value;
    const route = scope === 'my' ? `/projects/myprojects/${projectId}` : `/projects/allprojects/${projectId}`;
    window.location.href = route;
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
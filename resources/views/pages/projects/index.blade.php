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
document.addEventListener('DOMContentLoaded', function() {
    let currentData = [];
    
    loadProjects();
    
    document.getElementById('statusFilter').addEventListener('change', filterProjects);
    document.getElementById('departmentFilter').addEventListener('change', filterProjects);
    document.getElementById('searchInput').addEventListener('input', ARGPortal.debounce(filterProjects, 300));
    document.getElementById('clearFilters').addEventListener('click', clearFilters);
    
    async function loadProjects() {
        document.getElementById('loadingState').style.display = 'block';
        
        try {
            currentData = await API.getAllProjects();
            displayProjects(currentData);
            updateStats();
            populateFilters();
        } catch (error) {
            ARGPortal.showError('Failed to load projects');
            document.getElementById('loadingState').style.display = 'none';
        }
    }
    
    function displayProjects(data) {
        document.getElementById('loadingState').style.display = 'none';
        const tbody = document.getElementById('projectsTableBody');
        tbody.innerHTML = '';
        
        data.forEach(function(project) {
            const statusBadge = getStatusBadge(project.status || project.projectstatus);
            const progress = project.progress || 0;
            const startDate = project.start_date ? 
                new Date(project.start_date).toLocaleDateString() : 'N/A';
            
            tbody.innerHTML += `
                <tr>
                    <td>
                        <div class="fw-medium">${project.project_number || project.researchnumber || 'N/A'}</div>
                        <small class="text-muted">${project.title || project.proposal?.researchtitle || 'Untitled'}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="stats-icon primary me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                <i class="bi bi-person"></i>
                            </div>
                            ${project.principal_investigator || project.applicant?.name || 'N/A'}
                        </div>
                    </td>
                    <td>${project.department_name || project.proposal?.department?.shortname || 'N/A'}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="progress me-2" style="width: 60px; height: 6px;">
                                <div class="progress-bar" style="width: ${progress}%"></div>
                            </div>
                            <small>${progress}%</small>
                        </div>
                    </td>
                    <td>KES ${ARGPortal.formatNumber(project.budget || project.proposal?.grantitem?.amount || 0)}</td>
                    <td>${startDate}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="/projects/view/${project.id || project.researchid}" 
                               class="btn btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            ${(project.status === 'active' || project.projectstatus === 'Active') ? 
                                `<button class="btn btn-outline-warning" onclick="pauseProject(${project.id || project.researchid})" title="Pause">
                                    <i class="bi bi-pause"></i>
                                </button>` : ''
                            }
                            ${(project.status === 'paused' || project.projectstatus === 'Paused') ? 
                                `<button class="btn btn-outline-success" onclick="resumeProject(${project.id || project.researchid})" title="Resume">
                                    <i class="bi bi-play"></i>
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
            'active': '<span class="badge bg-success">Active</span>',
            'paused': '<span class="badge bg-warning">Paused</span>',
            'completed': '<span class="badge bg-info">Completed</span>',
            'cancelled': '<span class="badge bg-danger">Cancelled</span>'
        };
        return badges[status.toLowerCase()] || '<span class="badge bg-secondary">Unknown</span>';
    }
    
    function updateStats() {
        const total = currentData.length;
        const active = currentData.filter(p => (p.status || p.projectstatus || '').toLowerCase() === 'active').length;
        const completed = currentData.filter(p => (p.status || p.projectstatus || '').toLowerCase() === 'completed').length;
        const totalFunding = currentData.reduce((sum, p) => sum + (p.budget || p.proposal?.grantitem?.amount || 0), 0);
        
        document.getElementById('totalProjects').textContent = total;
        document.getElementById('activeProjects').textContent = active;
        document.getElementById('completedProjects').textContent = completed;
        document.getElementById('totalFunding').textContent = (totalFunding / 1000000).toFixed(1);
    }
    
    function populateFilters() {
        const departments = [...new Set(currentData.map(p => p.department_name || p.proposal?.department?.shortname).filter(Boolean))];
        const departmentFilter = document.getElementById('departmentFilter');
        
        departments.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept;
            option.textContent = dept;
            departmentFilter.appendChild(option);
        });
    }
    
    function filterProjects() {
        const status = document.getElementById('statusFilter').value.toLowerCase();
        const department = document.getElementById('departmentFilter').value;
        const search = document.getElementById('searchInput').value.toLowerCase();
        
        let filtered = currentData.filter(function(project) {
            const matchesStatus = !status || (project.status || project.projectstatus || '').toLowerCase() === status;
            const matchesDepartment = !department || (project.department_name || project.proposal?.department?.shortname) === department;
            const matchesSearch = !search || 
                (project.title || project.proposal?.researchtitle || '').toLowerCase().includes(search) ||
                (project.principal_investigator || project.applicant?.name || '').toLowerCase().includes(search);
            
            return matchesStatus && matchesDepartment && matchesSearch;
        });
        
        displayProjects(filtered);
    }
    
    function clearFilters() {
        document.getElementById('statusFilter').value = '';
        document.getElementById('departmentFilter').value = '';
        document.getElementById('searchInput').value = '';
        displayProjects(currentData);
    }
    
    window.pauseProject = async function(id) {
        if (!confirm('Pause this project?')) return;
        try {
            await fetch(`/api/projects/${id}/pause`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
            ARGPortal.showSuccess('Project paused successfully');
            loadProjects();
        } catch (error) {
            ARGPortal.showError('Failed to pause project');
        }
    };
    
    window.resumeProject = async function(id) {
        if (!confirm('Resume this project?')) return;
        try {
            await fetch(`/api/projects/${id}/resume`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
            ARGPortal.showSuccess('Project resumed successfully');
            loadProjects();
        } catch (error) {
            ARGPortal.showError('Failed to resume project');
        }
    };
    
    window.exportProjects = function() {
        ARGPortal.showSuccess('Export started');
    };
});
</script>
@endpush
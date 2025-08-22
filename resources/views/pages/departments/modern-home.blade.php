@extends('layouts.app')

@section('title', 'Departments - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Departments</h2>
            <p class="text-muted mb-0">Manage university departments and schools</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                <i class="bi bi-building-add me-2"></i>Add School
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                <i class="bi bi-plus-circle me-2"></i>Add Department
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stats-number" id="totalSchools">0</div>
                <div class="stats-label">Schools</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <div class="stats-number" id="totalDepartments">0</div>
                <div class="stats-label">Departments</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stats-number" id="totalStaff">0</div>
                <div class="stats-label">Staff Members</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="form-card mb-4">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-medium">School</label>
                <select class="form-select" id="schoolFilter">
                    <option value="">All Schools</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Search</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search departments...">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100" id="clearFilters">
                    <i class="bi bi-x-circle me-2"></i>Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Departments Grid -->
    <div class="row" id="departmentsGrid">
        <!-- Departments loaded via AJAX -->
    </div>
    
    <div id="loadingState" class="text-center py-5">
        <div class="spinner-border text-primary"></div>
        <p class="mt-2 text-muted">Loading departments...</p>
    </div>
</div>

<!-- Add School Modal -->
<div class="modal fade" id="addSchoolModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New School</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addSchoolForm" class="ajax-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">School Name *</label>
                        <input type="text" class="form-control" name="schoolname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add School</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addDepartmentForm" class="ajax-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">School *</label>
                        <select class="form-select" name="schoolidfk" id="schoolSelect" required>
                            <option value="">Select School</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department Name *</label>
                        <input type="text" class="form-control" name="departmentname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Department</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentData = [];
    let schools = [];
    
    loadData();
    
    $('#schoolFilter').on('change', filterDepartments);
    $('#searchInput').on('input', ARGPortal.debounce(filterDepartments, 300));
    $('#clearFilters').on('click', clearFilters);
    
    function loadData() {
        $('#loadingState').show();
        
        // Load schools and departments
        Promise.all([
            $.get("{{ route('api.schools.fetchallschools') }}"),
            $.get("{{ route('api.departments.fetchalldepartments') }}")
        ]).then(function([schoolsResponse, departmentsResponse]) {
            schools = schoolsResponse.data || [];
            currentData = departmentsResponse.data || [];
            
            populateSchoolFilters();
            displayDepartments(currentData);
            updateStats();
        }).catch(function() {
            ARGPortal.showError('Failed to load data');
            $('#loadingState').hide();
        });
    }
    
    function populateSchoolFilters() {
        const schoolFilter = $('#schoolFilter');
        const schoolSelect = $('#schoolSelect');
        
        schools.forEach(function(school) {
            const option = `<option value="${school.schoolid}">${school.schoolname}</option>`;
            schoolFilter.append(option);
            schoolSelect.append(option);
        });
    }
    
    function displayDepartments(data) {
        $('#loadingState').hide();
        const grid = $('#departmentsGrid');
        grid.empty();
        
        if (data.length === 0) {
            grid.append(`
                <div class="col-12 text-center py-5">
                    <i class="bi bi-building display-1 text-muted"></i>
                    <h5 class="mt-3">No Departments Found</h5>
                    <p class="text-muted">Add your first department to get started.</p>
                </div>
            `);
            return;
        }
        
        data.forEach(function(dept) {
            const school = schools.find(s => s.schoolid == dept.schoolidfk);
            
            grid.append(`
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stats-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="stats-icon success">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('pages.departments.viewdepartment', '') }}/${dept.departmentid}">
                                        <i class="bi bi-eye me-2"></i>View Details
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('pages.departments.editdepartment', '') }}/${dept.departmentid}">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                        <h6 class="fw-bold">${dept.departmentname}</h6>
                        <p class="text-muted small mb-2">${school ? school.schoolname : 'N/A'}</p>
                        <p class="text-muted small">${dept.description || 'No description available'}</p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between text-muted small">
                                <span>Staff: ${dept.staff_count || 0}</span>
                                <span>Created: ${new Date(dept.created_at).toLocaleDateString()}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        });
    }
    
    function filterDepartments() {
        const schoolId = $('#schoolFilter').val();
        const search = $('#searchInput').val().toLowerCase();
        
        let filtered = currentData.filter(function(dept) {
            const matchesSchool = !schoolId || dept.schoolidfk == schoolId;
            const matchesSearch = !search || 
                dept.departmentname.toLowerCase().includes(search) ||
                (dept.description && dept.description.toLowerCase().includes(search));
            
            return matchesSchool && matchesSearch;
        });
        
        displayDepartments(filtered);
    }
    
    function clearFilters() {
        $('#schoolFilter').val('');
        $('#searchInput').val('');
        displayDepartments(currentData);
    }
    
    function updateStats() {
        $('#totalSchools').text(schools.length);
        $('#totalDepartments').text(currentData.length);
        $('#totalStaff').text(currentData.reduce((sum, dept) => sum + (dept.staff_count || 0), 0));
    }
    
    // Form submissions
    $('#addSchoolForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('api.schools.post') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function() {
                ARGPortal.showSuccess('School added successfully');
                $('#addSchoolModal').modal('hide');
                loadData();
            },
            error: function() {
                ARGPortal.showError('Failed to add school');
            }
        });
    });
    
    $('#addDepartmentForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('api.departments.post') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function() {
                ARGPortal.showSuccess('Department added successfully');
                $('#addDepartmentModal').modal('hide');
                loadData();
            },
            error: function() {
                ARGPortal.showError('Failed to add department');
            }
        });
    });
});
</script>
@endpush
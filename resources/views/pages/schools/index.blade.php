@extends('layouts.app')

@section('title', 'Schools & Departments - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Schools & Departments</h2>
            <p class="text-muted mb-0">Manage university schools and departments</p>
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
        <div class="col-md-6">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stats-number" id="totalSchools">0</div>
                <div class="stats-label">Schools</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <div class="stats-number" id="totalDepartments">0</div>
                <div class="stats-label">Departments</div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="form-card mb-4">
        <div class="row align-items-end">
            <div class="col-md-9">
                <label class="form-label fw-medium">Search Schools & Departments</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search by name or description...">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100" id="clearSearch">
                    <i class="bi bi-x-circle me-2"></i>Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Schools with Departments -->
    <div id="schoolsContainer">
        <!-- Content loaded via AJAX -->
    </div>
    
    <div id="loadingState" class="text-center py-5">
        <div class="spinner-border text-primary"></div>
        <p class="mt-2 text-muted">Loading schools and departments...</p>
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
            <form id="addSchoolForm">
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
            <form id="addDepartmentForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">School *</label>
                        <select class="form-select" name="schoolfk" id="schoolSelect" required>
                            <option value="">Select School</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department Name *</label>
                        <input type="text" class="form-control" name="shortname" required>
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
    let schools = [];
    let departments = [];
    
    loadData();
    
    $('#searchInput').on('input', debounce(filterData, 300));
    $('#clearSearch').on('click', () => { $('#searchInput').val(''); displayData(); });
    
    async function loadData() {
        $('#loadingState').show();
        
        try {
            console.log('Loading schools and departments...');
            
            const [schoolsRes, deptsRes] = await Promise.all([
                $.get('/api/v1/schools').fail(function(xhr) {
                    console.error('Schools API error:', xhr.responseJSON || xhr.responseText);
                }),
                $.get('/api/v1/departments').fail(function(xhr) {
                    console.error('Departments API error:', xhr.responseJSON || xhr.responseText);
                })
            ]);
            
            console.log('Schools response:', schoolsRes);
            console.log('Departments response:', deptsRes);
            
            schools = schoolsRes.data || schoolsRes || [];
            departments = deptsRes.data || deptsRes || [];
            
            console.log('Processed schools:', schools.length);
            console.log('Processed departments:', departments.length);
            
            populateSchoolSelect();
            displayData();
            updateStats();
        } catch (error) {
            console.error('Load error:', error);
            if (error.responseJSON) {
                console.error('Error details:', error.responseJSON);
                console.error('API Error:', error.responseJSON.message || 'Failed to load schools and departments');
            } else {
                console.error('Failed to load schools and departments');
            }
        } finally {
            $('#loadingState').hide();
        }
    }
    
    function populateSchoolSelect() {
        const select = $('#schoolSelect');
        select.find('option:not(:first)').remove();
        schools.forEach(school => {
            select.append(`<option value="${school.schoolid}">${school.schoolname}</option>`);
        });
    }
    
    function displayData() {
        const container = $('#schoolsContainer');
        container.empty();
        
        if (schools.length === 0) {
            container.html(`
                <div class="text-center py-5">
                    <i class="bi bi-building display-1 text-muted"></i>
                    <h5 class="mt-3">No Schools Found</h5>
                    <p class="text-muted">Add your first school to get started.</p>
                </div>
            `);
            return;
        }
        
        schools.forEach(school => {
            const schoolDepts = departments.filter(d => d.schoolfk === school.schoolid);
            
            container.append(`
                <div class="form-card mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-1">${school.schoolname}</h5>
                            <p class="text-muted mb-0">${school.description || 'No description'}</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/schools/view/${school.schoolid}">
                                    <i class="bi bi-eye me-2"></i>View School
                                </a></li>
                                <li><a class="dropdown-item" href="/schools/edit/${school.schoolid}">
                                    <i class="bi bi-pencil me-2"></i>Edit School
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="row">
                        ${schoolDepts.length === 0 ? 
                            '<div class="col-12"><p class="text-muted">No departments in this school</p></div>' :
                            schoolDepts.map(dept => `
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">${dept.shortname}</h6>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-link p-0" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="/departments/view/${dept.depid}">
                                                        <i class="bi bi-eye me-2"></i>View
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="/departments/edit/${dept.depid}">
                                                        <i class="bi bi-pencil me-2"></i>Edit
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-0">${dept.description || 'No description'}</p>
                                        <small class="text-muted">Staff: ${dept.staff_count || 0}</small>
                                    </div>
                                </div>
                            `).join('')
                        }
                    </div>
                </div>
            `);
        });
    }
    
    function filterData() {
        const search = $('#searchInput').val().toLowerCase();
        if (!search) {
            displayData();
            return;
        }
        
        const filteredSchools = schools.filter(school => 
            school.schoolname.toLowerCase().includes(search) ||
            (school.description && school.description.toLowerCase().includes(search)) ||
            departments.some(dept => 
                dept.schoolfk === school.schoolid && 
                (dept.shortname.toLowerCase().includes(search) || 
                 (dept.description && dept.description.toLowerCase().includes(search)))
            )
        );
        
        const container = $('#schoolsContainer');
        container.empty();
        
        filteredSchools.forEach(school => {
            const schoolDepts = departments.filter(d => 
                d.schoolfk === school.schoolid &&
                (d.shortname.toLowerCase().includes(search) || 
                 (d.description && d.description.toLowerCase().includes(search)) ||
                 school.schoolname.toLowerCase().includes(search) ||
                 (school.description && school.description.toLowerCase().includes(search)))
            );
            
            container.append(`
                <div class="form-card mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-1">${school.schoolname}</h5>
                            <p class="text-muted mb-0">${school.description || 'No description'}</p>
                        </div>
                    </div>
                    <div class="row">
                        ${schoolDepts.map(dept => `
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-1">${dept.shortname}</h6>
                                    <p class="text-muted small mb-0">${dept.description || 'No description'}</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `);
        });
    }
    
    function updateStats() {
        $('#totalSchools').text(schools.length);
        $('#totalDepartments').text(departments.length);
    }
    
    // Form submissions
    $('#addSchoolForm').on('submit', function(e) {
        e.preventDefault();
        $.post('/api/v1/schools', $(this).serialize())
            .done(() => {
                console.log('School added successfully');
                $('#addSchoolModal').modal('hide');
                this.reset();
                loadData();
            })
            .fail(() => console.error('Failed to add school'));
    });
    
    $('#addDepartmentForm').on('submit', function(e) {
        e.preventDefault();
        $.post('/api/v1/departments', $(this).serialize())
            .done(() => {
                console.log('Department added successfully');
                $('#addDepartmentModal').modal('hide');
                this.reset();
                loadData();
            })
            .fail(() => console.error('Failed to add department'));
    });
    
    // Simple debounce function
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
});
</script>
@endpush
@extends('layouts.app')

@section('title', 'Schools - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Schools</h2>
            <p class="text-muted mb-0">Manage university schools and faculties</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
            <i class="bi bi-building-add me-2"></i>Add School
        </button>
    </div>

    <!-- Search -->
    <div class="form-card mb-4">
        <div class="row align-items-end">
            <div class="col-md-9">
                <label class="form-label fw-medium">Search Schools</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search by name or description...">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100" id="clearSearch">
                    <i class="bi bi-x-circle me-2"></i>Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Schools Grid -->
    <div class="row" id="schoolsGrid">
        <!-- Schools loaded via AJAX -->
    </div>
    
    <div id="loadingState" class="text-center py-5">
        <div class="spinner-border text-primary"></div>
        <p class="mt-2 text-muted">Loading schools...</p>
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
                        <textarea class="form-control" name="description" rows="4" placeholder="Brief description of the school..."></textarea>
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

<!-- Edit School Modal -->
<div class="modal fade" id="editSchoolModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit School</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSchoolForm" class="ajax-form">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="editSchoolId" name="school_id">
                    <div class="mb-3">
                        <label class="form-label">School Name *</label>
                        <input type="text" class="form-control" id="editSchoolName" name="schoolname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="editSchoolDescription" name="description" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update School</button>
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
    
    loadSchools();
    
    $('#searchInput').on('input', ARGPortal.debounce(filterSchools, 300));
    $('#clearSearch').on('click', clearSearch);
    
    function loadSchools() {
        $('#loadingState').show();
        
        $.ajax({
            url: "{{ route('api.schools.fetchallschools') }}",
            type: 'GET',
            success: function(response) {
                currentData = response.data || [];
                displaySchools(currentData);
            },
            error: function() {
                ARGPortal.showError('Failed to load schools');
                $('#loadingState').hide();
            }
        });
    }
    
    function displaySchools(data) {
        $('#loadingState').hide();
        const grid = $('#schoolsGrid');
        grid.empty();
        
        if (data.length === 0) {
            grid.append(`
                <div class="col-12 text-center py-5">
                    <i class="bi bi-building display-1 text-muted"></i>
                    <h5 class="mt-3">No Schools Found</h5>
                    <p class="text-muted">Add your first school to get started.</p>
                </div>
            `);
            return;
        }
        
        data.forEach(function(school) {
            grid.append(`
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stats-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="stats-icon primary">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('pages.schools.viewschool', '') }}/${school.schoolid}">
                                        <i class="bi bi-eye me-2"></i>View Details
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="editSchool(${school.schoolid}, '${school.schoolname}', '${school.description || ''}')">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2">${school.schoolname}</h5>
                        <p class="text-muted small mb-3">${school.description || 'No description available'}</p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Departments: ${school.departments_count || 0}</small>
                                <small class="text-muted">${new Date(school.created_at).toLocaleDateString()}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        });
    }
    
    function filterSchools() {
        const search = $('#searchInput').val().toLowerCase();
        
        let filtered = currentData.filter(function(school) {
            return !search || 
                school.schoolname.toLowerCase().includes(search) ||
                (school.description && school.description.toLowerCase().includes(search));
        });
        
        displaySchools(filtered);
    }
    
    function clearSearch() {
        $('#searchInput').val('');
        displaySchools(currentData);
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
                $(this)[0].reset();
                loadSchools();
            }.bind(this),
            error: function() {
                ARGPortal.showError('Failed to add school');
            }
        });
    });
    
    $('#editSchoolForm').on('submit', function(e) {
        e.preventDefault();
        const schoolId = $('#editSchoolId').val();
        
        $.ajax({
            url: `{{ route('api.schools.updateschool', '') }}/${schoolId}`,
            type: 'POST',
            data: $(this).serialize(),
            success: function() {
                ARGPortal.showSuccess('School updated successfully');
                $('#editSchoolModal').modal('hide');
                loadSchools();
            },
            error: function() {
                ARGPortal.showError('Failed to update school');
            }
        });
    });
    
    window.editSchool = function(id, name, description) {
        $('#editSchoolId').val(id);
        $('#editSchoolName').val(name);
        $('#editSchoolDescription').val(description);
        $('#editSchoolModal').modal('show');
    };
});
</script>
@endpush
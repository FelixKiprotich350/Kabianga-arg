@extends('layouts.app')

@section('title', 'Department Details - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ $department->departmentname ?? 'Department Details' }}</h2>
            <p class="text-muted mb-0">{{ $department->school->schoolname ?? 'N/A' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pages.departments.editdepartment', $department->departmentid) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('pages.departments.home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <!-- Department Information -->
            <div class="form-card mb-4">
                <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Department Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Department Name</label>
                        <p class="fw-medium">{{ $department->departmentname ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">School</label>
                        <p class="fw-medium">{{ $department->school->schoolname ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Description</label>
                        <p class="fw-medium">{{ $department->description ?? 'No description available' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Created</label>
                        <p class="fw-medium">{{ $department->created_at ? $department->created_at->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Last Updated</label>
                        <p class="fw-medium">{{ $department->updated_at ? $department->updated_at->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Staff Members -->
            <div class="form-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Staff Members</h5>
                    <span class="badge bg-primary" id="staffCount">0</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="staffTableBody">
                            <!-- Staff loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <div id="noStaff" class="text-center py-4" style="display: none;">
                    <i class="bi bi-people display-4 text-muted"></i>
                    <p class="text-muted mt-2">No staff members found in this department</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Statistics -->
            <div class="form-card mb-4">
                <h6 class="mb-3"><i class="bi bi-graph-up me-2"></i>Department Statistics</h6>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stats-number text-primary" id="totalStaff">0</div>
                        <div class="stats-label">Staff</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stats-number text-success" id="totalProposals">0</div>
                        <div class="stats-label">Proposals</div>
                    </div>
                    <div class="col-6">
                        <div class="stats-number text-warning" id="activeProjects">0</div>
                        <div class="stats-label">Projects</div>
                    </div>
                    <div class="col-6">
                        <div class="stats-number text-info" id="totalFunding">0</div>
                        <div class="stats-label">Funding (K)</div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="form-card">
                <h6 class="mb-3"><i class="bi bi-clock me-2"></i>Recent Activity</h6>
                <div id="recentActivity">
                    <!-- Activity loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    loadDepartmentData();
    
    function loadDepartmentData() {
        // Load staff members
        loadStaffMembers();
        
        // Load department statistics
        loadStatistics();
        
        // Load recent activity
        loadRecentActivity();
    }
    
    function loadStaffMembers() {
        $.ajax({
            url: "{{ route('api.users.fetchallusers') }}",
            type: 'GET',
            data: { department_id: {{ $department->departmentid }} },
            success: function(response) {
                const staff = response.data || [];
                displayStaffMembers(staff);
                $('#staffCount').text(staff.length);
                $('#totalStaff').text(staff.length);
            },
            error: function() {
                $('#noStaff').show();
            }
        });
    }
    
    function displayStaffMembers(staff) {
        const tbody = $('#staffTableBody');
        tbody.empty();
        
        if (staff.length === 0) {
            $('#noStaff').show();
            return;
        }
        
        staff.forEach(function(member) {
            const joinedDate = new Date(member.created_at).toLocaleDateString();
            const statusBadge = member.email_verified_at ? 
                '<span class="badge bg-success">Active</span>' : 
                '<span class="badge bg-warning">Inactive</span>';
            
            tbody.append(`
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="stats-icon primary me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                <i class="bi bi-person"></i>
                            </div>
                            ${member.name}
                        </div>
                    </td>
                    <td>${member.email}</td>
                    <td><span class="badge bg-info">${member.role || 'Staff'}</span></td>
                    <td>${joinedDate}</td>
                    <td>
                        <a href="{{ route('pages.users.viewsingleuser', '') }}/${member.userid}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            `);
        });
    }
    
    function loadStatistics() {
        // Mock statistics - replace with actual API calls
        $('#totalProposals').text('12');
        $('#activeProjects').text('5');
        $('#totalFunding').text('2.5M');
    }
    
    function loadRecentActivity() {
        const activities = [
            {
                icon: 'person-plus',
                color: 'success',
                text: 'New staff member joined',
                time: '2 hours ago'
            },
            {
                icon: 'file-text',
                color: 'primary',
                text: 'Proposal submitted',
                time: '1 day ago'
            },
            {
                icon: 'check-circle',
                color: 'success',
                text: 'Project approved',
                time: '3 days ago'
            }
        ];
        
        const activityContainer = $('#recentActivity');
        
        activities.forEach(function(activity) {
            activityContainer.append(`
                <div class="d-flex align-items-start mb-3">
                    <div class="stats-icon ${activity.color} me-3" style="width: 30px; height: 30px; font-size: 0.8rem;">
                        <i class="bi bi-${activity.icon}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1 small">${activity.text}</p>
                        <small class="text-muted">${activity.time}</small>
                    </div>
                </div>
            `);
        });
    }
});
</script>
@endpush
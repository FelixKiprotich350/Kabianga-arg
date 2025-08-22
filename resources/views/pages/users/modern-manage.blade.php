@extends('layouts.app')

@section('title', 'User Management - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">User Management</h2>
            <p class="text-muted mb-0">Manage system users and permissions</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus me-2"></i>Add User
        </button>
    </div>

    <!-- Filters -->
    <div class="form-card mb-4">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-medium">Role</label>
<select class="form-select" id="roleFilter">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="committee">Committee</option>
                    <option value="researcher">Researcher</option>
                    <option value="guest">Guest</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-medium">Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium">Search</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search users...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" id="clearFilters">
                    <i class="bi bi-x-circle me-2"></i>Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- Data loaded via AJAX -->
                </tbody>
            </table>
        </div>
        
        <div id="loadingState" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 text-muted">Loading users...</p>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm" class="ajax-form">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <div class="row">
<div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" name="phonenumber">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">PF Number</label>
                            <input type="text" class="form-control" name="pfno">
                        </div>
                    </div>
                    <div class="row">
<div class="col-md-6 mb-3">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="departmentidfk">
                                <option value="">Select Department</option>
                                <!-- Departments loaded via AJAX -->
                            </select>
                        </div>
                    </div>
                    <div class="row">
<div class="col-md-6 mb-3">
                            <label class="form-label">Role *</label>
                            <select class="form-select" name="role" required>
                                <option value="2">Researcher</option>
                                <option value="1">Committee</option>
                                <option value="3">Guest</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
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
    
loadUsers();
    loadDepartments();
    
    $('#roleFilter, #statusFilter').on('change', filterUsers);
    $('#searchInput').on('input', ARGPortal.debounce(filterUsers, 300));
    $('#clearFilters').on('click', clearFilters);
    
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize() + '&password=defaultpass123&password_confirmation=defaultpass123';
        $.ajax({
            url: "{{ route('register.submit') }}",
            type: 'POST',
            data: formData,
            success: function() {
                ARGPortal.showSuccess('User added successfully');
                $('#addUserModal').modal('hide');
                $('#addUserForm')[0].reset();
                loadUsers();
            },
            error: function() {
                ARGPortal.showError('Failed to add user');
            }
        });
    });
    
function loadUsers() {
        $('#loadingState').show();
        
        $.ajax({
            url: "{{ route('api.users.fetchallusers') }}",
            type: 'GET',
            success: function(response) {
                currentData = response.data || response || [];
                displayUsers(currentData);
            },
            error: function() {
                ARGPortal.showError('Failed to load users');
                $('#loadingState').hide();
            }
        });
    }
    
    function loadDepartments() {
        $.ajax({
            url: "{{ route('api.departments.fetchalldepartments') }}",
            type: 'GET',
            success: function(response) {
                const departments = response.data || response || [];
                const select = $('select[name="departmentidfk"]');
                departments.forEach(function(dept) {
                    select.append(`<option value="${dept.departmentid}">${dept.departmentname}</option>`);
                });
            }
        });
    }
    
function displayUsers(data) {
        $('#loadingState').hide();
        const tbody = $('#usersTableBody');
        tbody.empty();
        
        if (data.length === 0) {
            tbody.append('<tr><td colspan="7" class="text-center text-muted">No users found</td></tr>');
            return;
        }
        
data.forEach(function(user) {
            const statusBadge = user.email_verified_at ? 
                '<span class="badge bg-success">Active</span>' : 
                '<span class="badge bg-warning">Pending</span>';
            
            const lastLogin = user.last_login_at ? 
                new Date(user.last_login_at).toLocaleDateString() : 'Never';
            
            const roleName = getRoleName(user.role, user.isadmin);
            const departmentName = user.department ? user.department.departmentname : 'N/A';
            
            tbody.append(`
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="stats-icon primary me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <div class="fw-medium">${user.name || 'N/A'}</div>
                                <small class="text-muted">PF: ${user.pfno || 'N/A'}</small>
                            </div>
                        </div>
                    </td>
                    <td>${user.email || 'N/A'}</td>
                    <td>${departmentName}</td>
                    <td><span class="badge bg-info">${roleName}</span></td>
                    <td>${statusBadge}</td>
                    <td>${lastLogin}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('pages.users.viewsingleuser', '') }}/${user.userid}" 
                               class="btn btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button class="btn btn-outline-warning" onclick="resetPassword(${user.userid})" title="Reset Password">
                                <i class="bi bi-key"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
        });
    }
    
    function getRoleName(role, isAdmin) {
        if (isAdmin) return 'Admin';
        switch(role) {
            case 1: return 'Committee';
            case 2: return 'Researcher';
            case 3: return 'Guest';
            default: return 'User';
        }
    }
    
    function filterUsers() {
        const role = $('#roleFilter').val().toLowerCase();
        const status = $('#statusFilter').val();
        const search = $('#searchInput').val().toLowerCase();
        
        let filtered = currentData.filter(function(user) {
            const userRole = getRoleName(user.role, user.isadmin).toLowerCase();
            const matchesRole = !role || userRole === role;
            const matchesStatus = !status || 
                (status === 'active' && user.email_verified_at) ||
                (status === 'inactive' && !user.email_verified_at);
            const matchesSearch = !search || 
                (user.name && user.name.toLowerCase().includes(search)) ||
                (user.email && user.email.toLowerCase().includes(search)) ||
                (user.pfno && user.pfno.toLowerCase().includes(search));
            
            return matchesRole && matchesStatus && matchesSearch;
        });
        
        displayUsers(filtered);
    }
    
    function clearFilters() {
        $('#roleFilter, #statusFilter').val('');
        $('#searchInput').val('');
        displayUsers(currentData);
    }
    
    window.resetPassword = function(userId) {
        if (confirm('Reset password for this user?')) {
            $.post(`{{ route('api.users.resetpassword', '') }}/${userId}`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function() {
                ARGPortal.showSuccess('Password reset successfully');
            }).fail(function() {
                ARGPortal.showError('Failed to reset password');
            });
        }
    };
});t roleName = getRoleName(user.role, user.isadmin);
            const departmentName = user.department ? user.department.departmentname : 'N/A';
            
            tbody.append(`
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="stats-icon primary me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <div class="fw-medium">${user.name || 'N/A'}</div>
                                <small class="text-muted">PF: ${user.pfno || 'N/A'}</small>
                            </div>
                        </div>
                    </td>
                    <td>${user.email || 'N/A'}</td>
                    <td>${departmentName}</td>
                    <td><span class="badge bg-info">${roleName}</span></td>
                    <td>${statusBadge}</td>
                    <td>${lastLogin}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('pages.users.viewsingleuser', '') }}/${user.userid}" 
                               class="btn btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button class="btn btn-outline-warning" onclick="resetPassword(${user.userid})" title="Reset Password">
                                <i class="bi bi-key"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
        });
    }
    
    function getRoleName(role, isAdmin) {
        if (isAdmin) return 'Admin';
        switch(role) {
            case 1: return 'Committee';
            case 2: return 'Researcher';
            case 3: return 'Guest';
            default: return 'User';
        }
    }
    
    function filterUsers() {
        const role = $('#roleFilter').val().toLowerCase();
        const status = $('#statusFilter').val();
        const search = $('#searchInput').val().toLowerCase();
        
        let filtered = currentData.filter(function(user) {
            const userRole = getRoleName(user.role, user.isadmin).toLowerCase();
            const matchesRole = !role || userRole === role;
            const matchesStatus = !status || 
                (status === 'active' && user.email_verified_at) ||
                (status === 'inactive' && !user.email_verified_at);
            const matchesSearch = !search || 
                (user.name && user.name.toLowerCase().includes(search)) ||
                (user.email && user.email.toLowerCase().includes(search)) ||
                (user.pfno && user.pfno.toLowerCase().includes(search));
            
            return matchesRole && matchesStatus && matchesSearch;
        });
        
        displayUsers(filtered);
    }
    
    function clearFilters() {
        $('#roleFilter, #statusFilter').val('');
        $('#searchInput').val('');
        displayUsers(currentData);
    }
    
    window.resetPassword = function(userId) {
        if (confirm('Reset password for this user?')) {
            $.post(`{{ route('api.users.resetpassword', '') }}/${userId}`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function() {
                ARGPortal.showSuccess('Password reset successfully');
            }).fail(function() {
                ARGPortal.showError('Failed to reset password');
            });
        }
    };
});
            
            const roleName = getRoleName(user.role, user.isadmin);
            const departmentName = user.department ? user.department.departmentname : 'N/A';
            
            tbody.append(`
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="stats-icon primary me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <div class="fw-medium">${user.name || 'N/A'}</div>
                                <small class="text-muted">PF: ${user.pfno || 'N/A'}</small>
                            </div>
                        </div>
                    </td>
                    <td>${user.email || 'N/A'}</td>
                    <td>${departmentName}</td>
                    <td><span class="badge bg-info">${roleName}</span></td>
                    <td>${statusBadge}</td>
                    <td>${lastLogin}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('pages.users.viewsingleuser', '') }}/${user.userid}" 
                               class="btn btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button class="btn btn-outline-warning" onclick="resetPassword(${user.userid})" title="Reset Password">
                                <i class="bi bi-key"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
        });
    }
    
    function getRoleName(role, isAdmin) {
        if (isAdmin) return 'Admin';
        switch(role) {
            case 1: return 'Committee';
            case 2: return 'Researcher';
            case 3: return 'Guest';
            default: return 'User';
        }
    }
    
function filterUsers() {
        const role = $('#roleFilter').val().toLowerCase();
        const status = $('#statusFilter').val();
        const search = $('#searchInput').val().toLowerCase();
        
        let filtered = currentData.filter(function(user) {
            const userRole = getRoleName(user.role, user.isadmin).toLowerCase();
            const matchesRole = !role || userRole === role;
            const matchesStatus = !status || 
                (status === 'active' && user.email_verified_at) ||
                (status === 'inactive' && !user.email_verified_at);
            const matchesSearch = !search || 
                (user.name && user.name.toLowerCase().includes(search)) ||
                (user.email && user.email.toLowerCase().includes(search)) ||
                (user.pfno && user.pfno.toLowerCase().includes(search));
            
            return matchesRole && matchesStatus && matchesSearch;
        });
        
        displayUsers(filtered);
    }
    
    function clearFilters() {
        $('#roleFilter, #statusFilter').val('');
        $('#searchInput').val('');
        displayUsers(currentData);
    }
    
    window.resetPassword = function(userId) {
        if (confirm('Reset password for this user?')) {
            $.post(`{{ route('api.users.resetpassword', '') }}/${userId}`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function() {
                ARGPortal.showSuccess('Password reset successfully');
            }).fail(function() {
                ARGPortal.showError('Failed to reset password');
            });
        }
    };
});
</script>
@endpush
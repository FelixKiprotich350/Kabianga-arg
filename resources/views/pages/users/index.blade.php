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
                <label class="form-label fw-medium">Admin Status</label>
                <select class="form-select" id="adminFilter">
                    <option value="">All Users</option>
                    <option value="admin">Administrators</option>
                    <option value="user">Regular Users</option>
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
                        <th>Admin</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- Data loaded via API -->
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
            <form id="addUserForm">
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

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="editUserId">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="editUserName" name="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="editUserEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="editUserPhone" name="phonenumber">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PF Number</label>
                        <input type="text" class="form-control" id="editUserPF" name="pfno">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="resetPasswordForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="resetPasswordUserId">
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadUsersData();
    
    // Edit User Form
    document.getElementById('editUserForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const userId = document.getElementById('editUserId').value;
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        try {
            const response = await API.updateUser(userId, data);
            if (response.type === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                loadUsersData();
                showAlert('User updated successfully', 'success');
            } else {
                showAlert(response.message, 'error');
            }
        } catch (error) {
            showAlert('Failed to update user', 'error');
        }
    });
    
 
    // Reset Password Form
    document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const userId = document.getElementById('resetPasswordUserId').value;
        const password = this.password.value;
        const confirmation = this.password_confirmation.value;
        
        if (password !== confirmation) {
            showAlert('Passwords do not match', 'error');
            return;
        }
        
        try {
            const response = await API.resetUserPassword(userId, password);
            if (response.type === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal')).hide();
                showAlert('Password reset successfully', 'success');
                this.reset();
            } else {
                showAlert(response.message, 'error');
            }
        } catch (error) {
            showAlert('Failed to reset password', 'error');
        }
    });
});

// User Management Functions
window.viewUser = function(userId) {
    window.location.href = `/users/${userId}`;
};

window.editUser = function(userId) {
    // Get user data and populate form
    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    document.getElementById('editUserId').value = userId;
    modal.show();
};

window.managePermissions = function(userId) {
    window.location.href = `/users/${userId}/permissions`;
};

window.resetPassword = function(userId) {
    const modal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
    document.getElementById('resetPasswordUserId').value = userId;
    modal.show();
};

window.toggleUserStatus = async function(userId, isActive) {
    const action = isActive ? 'disable' : 'enable';
    if (!confirm(`Are you sure you want to ${action} this user?`)) return;
    
    try {
        const response = isActive ? await API.disableUser(userId) : await API.enableUser(userId);
        if (response.type === 'success') {
            loadUsersData();
            showAlert(`User ${action}d successfully`, 'success');
        } else {
            showAlert(response.message, 'error');
        }
    } catch (error) {
        showAlert(`Failed to ${action} user`, 'error');
    }
};

async function loadUsersData() {
    const loadingState = document.getElementById('loadingState');
    const tableBody = document.getElementById('usersTableBody');
    
    try {
        loadingState.style.display = 'block';
        const response = await fetch('/api/v1/users');
        const result = await response.json();
        
        if (result.success) {
            tableBody.innerHTML = '';
            result.data.forEach(user => {
                const row = `
                    <tr>
                        <td>${user.name || ''}</td>
                        <td>${user.email || ''}</td>
                        <td>-</td>
                        <td><span class="badge ${user.isadmin ? 'bg-warning' : 'bg-secondary'}">${user.isadmin ? 'Admin' : 'User'}</span></td>
                        <td><span class="badge ${user.isactive ? 'bg-success' : 'bg-danger'}">${user.isactive ? 'Active' : 'Inactive'}</span></td>
                        <td>-</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" onclick="viewUser('${user.userid}')" title="View User">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" onclick="editUser('${user.userid}')" title="Edit User">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" onclick="managePermissions('${user.userid}')" title="Manage Permissions">
                                <i class="bi bi-shield-check"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="resetPassword('${user.userid}')" title="Reset Password">
                                <i class="bi bi-key"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Failed to load users</td></tr>';
        }
    } catch (error) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Error loading users</td></tr>';
    } finally {
        loadingState.style.display = 'none';
    }
}

function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) alert.remove();
    }, 5000);
}
</script>
@endpush
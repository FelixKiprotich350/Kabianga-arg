@extends('layouts.app')

@section('title', 'User Permissions - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">User Permissions & Roles</h2>
            <p class="text-muted mb-0">Manage user {{ $user->name }} permissions and role assignments</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Users
        </a>
    </div>

    <div class="row">
        <!-- User Info Card -->
        <div class="col-md-4">
            <div class="form-card">
                <h5 class="mb-3">User Information</h5>
                <div class="mb-3">
                    <strong>Name:</strong> {{ $user->name }}
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> {{ $user->email }}
                </div>
                <div class="mb-3">
                    <strong>Current Role:</strong> 
                    <span class="badge bg-primary">{{ $roleNames[$user->role] ?? 'Unknown' }}</span>
                </div>
                <div class="mb-3">
                    <strong>Status:</strong>
                    <span class="badge {{ $user->isactive ? 'bg-success' : 'bg-danger' }}">
                        {{ $user->isactive ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="mb-3">
                    <strong>Super Admin:</strong>
                    <span class="badge {{ $user->isadmin ? 'bg-warning' : 'bg-secondary' }}">
                        {{ $user->isadmin ? 'Yes' : 'No' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Role & Status Management -->
        <div class="col-md-8">
            <div class="form-card mb-4">
                <h5 class="mb-3">Role & Status Management</h5>
                <form id="roleForm" action="/api/v1/users/{{ $user->userid }}/role" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">User Role</label>
                            <select class="form-select" name="role" required>
                                @foreach($roles as $roleId => $roleName)
                                    <option value="{{ $roleId }}" {{ $user->role == $roleId ? 'selected' : '' }}>
                                        {{ $roleName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Account Status</label>
                            <select class="form-select" name="isactive">
                                <option value="1" {{ $user->isactive ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$user->isactive ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="isadmin" value="1" 
                                   {{ $user->isadmin ? 'checked' : '' }} id="isAdminCheck">
                            <label class="form-check-label" for="isAdminCheck">
                                Super Administrator (bypasses all permission checks)
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Role & Status</button>
                </form>
            </div>

            <!-- Role Timeline Management -->
            <div class="form-card mb-4">
                <h5 class="mb-3">Role Timeline</h5>
                <form id="assignRoleForm" class="mb-3">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-select" name="role_type" required>
                                <option value="researcher">Researcher</option>
                                <option value="committee_member">Committee Member</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" name="end_date" placeholder="End Date (Optional)">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Assign Role</button>
                        </div>
                    </div>
                </form>
                <div id="roleTimeline"></div>
            </div>

            <!-- Permissions Management -->
            <div class="form-card">
                <h5 class="mb-3">Additional Permissions</h5>
                
                <!-- Role-based Permissions -->
                <div class="mb-4">
                    <h6 class="text-muted mb-3">Role-based Permissions (Automatic)</h6>
                    <div class="row">
                        @forelse($rolePermissions as $permission)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>{{ $permission->menuname }}</span>
                                    <small class="text-muted ms-auto">{{ $permission->shortname }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">No role-based permissions found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Additional Permissions -->
                <div>
                    <h6 class="text-muted mb-3">Additional Permissions</h6>
                    <form id="permissionsForm" action="/api/v1/users/{{ $user->userid }}/permissions" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            @foreach($availablePermissions as $permission)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="permissions[]" value="{{ $permission->pid }}"
                                               {{ $userPermissions->contains('pid', $permission->pid) ? 'checked' : '' }}
                                               id="perm_{{ $permission->pid }}">
                                        <label class="form-check-label" for="perm_{{ $permission->pid }}">
                                            {{ $permission->menuname }}
                                            <small class="text-muted d-block">{{ $permission->shortname }}</small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">Update Permissions</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearAllPermissions()">Clear All</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Role form submission
    document.getElementById('roleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Role and status updated successfully', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert(data.message || 'Failed to update role', 'error');
            }
        })
        .catch(error => {
            showAlert('An error occurred', 'error');
        });
    });

    // Role assignment form
    document.getElementById('assignRoleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(`/users/{{ $user->userid }}/assign-role`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Role assigned successfully', 'success');
                loadRoleTimeline();
                this.reset();
            } else {
                showAlert(data.message || 'Failed to assign role', 'error');
            }
        })
        .catch(error => {
            showAlert('An error occurred', 'error');
        });
    });

    // Load role timeline
    function loadRoleTimeline() {
        fetch(`/users/{{ $user->userid }}/roles`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const timeline = document.getElementById('roleTimeline');
                timeline.innerHTML = '';
                
                data.data.forEach(role => {
                    const isActive = role.is_active && new Date(role.start_date) <= new Date() && 
                                   (!role.end_date || new Date(role.end_date) >= new Date());
                    
                    timeline.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center p-2 border rounded mb-2 ${isActive ? 'bg-light-success' : 'bg-light'}">
                            <div>
                                <strong>${role.role_type.replace('_', ' ').toUpperCase()}</strong>
                                <small class="text-muted d-block">${role.start_date} ${role.end_date ? '- ' + role.end_date : '- Ongoing'}</small>
                            </div>
                            <div>
                                <span class="badge ${isActive ? 'bg-success' : 'bg-secondary'}">${isActive ? 'Active' : 'Inactive'}</span>
                                ${isActive ? `<button class="btn btn-sm btn-outline-danger ms-2" onclick="deactivateRole('${role.id}')">End</button>` : ''}
                            </div>
                        </div>
                    `;
                });
            }
        });
    }

    loadRoleTimeline();

    // Permissions form submission
    document.getElementById('permissionsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Permissions updated successfully', 'success');
            } else {
                showAlert(data.message || 'Failed to update permissions', 'error');
            }
        })
        .catch(error => {
            showAlert('An error occurred', 'error');
        });
    });
});

function clearAllPermissions() {
    if (confirm('Are you sure you want to clear all additional permissions?')) {
        document.querySelectorAll('#permissionsForm input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
        });
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
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

        <!-- Status Management -->
        <div class="col-md-8">
            <div class="form-card mb-4">
                <h5 class="mb-3">Account Status</h5>
                <form id="statusForm" action="/api/v1/users/{{ $user->userid }}/status" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Account Status</label>
                            <select class="form-select" name="isactive">
                                <option value="1" {{ $user->isactive ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$user->isactive ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>

            @if(auth()->user()->issuperadmin())
            <!-- Super Admin Management -->
            <div class="form-card mb-4">
                <h5 class="mb-3">Super Administrator Role</h5>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Super administrators bypass all permission checks and have full system access.
                </div>
                <form id="superAdminForm" action="/api/v1/users/{{ $user->userid }}/superadmin" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="isadmin" value="1" 
                               {{ $user->isadmin ? 'checked' : '' }} id="isAdminCheck">
                        <label class="form-check-label" for="isAdminCheck">
                            Grant Super Administrator privileges
                        </label>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Super Admin Status</button>
                </form>
            </div>
            @endif

            <!-- Permissions Management -->
            <div class="form-card">
                <h5 class="mb-3">User Permissions</h5>
                <form id="permissionsForm" action="/api/v1/users/{{ $user->userid }}/permissions" method="POST">
                    @csrf
                    @method('PATCH')
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status form submission
    document.getElementById('statusForm').addEventListener('submit', function(e) {
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
                showAlert('Status updated successfully', 'success');
            } else {
                showAlert(data.message || 'Failed to update status', 'error');
            }
        })
        .catch(error => {
            showAlert('An error occurred', 'error');
        });
    });

    // Super Admin form submission
    const superAdminForm = document.getElementById('superAdminForm');
    if (superAdminForm) {
        superAdminForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to change super administrator status? This will grant/revoke full system access.')) {
                return;
            }
            
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
                    showAlert('Super admin status updated successfully', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(data.message || 'Failed to update super admin status', 'error');
                }
            })
            .catch(error => {
                showAlert('An error occurred', 'error');
            });
        });
    }

    // Permissions form submission
    document.getElementById('permissionsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get selected permissions
        const selectedPermissions = [];
        document.querySelectorAll('input[name="permissions[]"]:checked').forEach(checkbox => {
            selectedPermissions.push(checkbox.value);
        });
        
        console.log('Selected permissions:', selectedPermissions);
        
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('_method', 'PATCH');
        
        selectedPermissions.forEach(permission => {
            formData.append('permissions[]', permission);
        });
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            if (data.success) {
                showAlert('Permissions updated successfully', 'success');
            } else {
                showAlert(data.message || 'Failed to update permissions', 'error');
            }
        })
        .catch(error => {
            console.log('Error:', error);
            showAlert('An error occurred', 'error');
        });
    });
});

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

function clearAllPermissions() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}
</script>
@endpush
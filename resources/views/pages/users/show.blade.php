@extends('layouts.app')

@section('title', 'User Details - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">User Details</h2>
            <p class="text-muted mb-0">View and manage user information</p>
        </div>
        <a href="{{ route('pages.users.manage') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Users
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- User Profile Card -->
            <div class="form-card text-center">
                <div class="stats-icon primary mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="bi bi-person"></i>
                </div>
                <h4>{{ $user->name ?? 'N/A' }}</h4>
                <p class="text-muted">{{ $user->email ?? 'N/A' }}</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    @if($user->email_verified_at)
                        <span class="badge bg-success">Verified</span>
                    @else
                        <span class="badge bg-warning">Unverified</span>
                    @endif
                    <span class="badge bg-info">{{ ucfirst($user->role ?? 'Applicant') }}</span>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-warning" onclick="resetPassword()">
                        <i class="bi bi-key me-2"></i>Reset Password
                    </button>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal">
                        <i class="bi bi-pencil me-2"></i>Edit Details
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- User Information -->
            <div class="form-card mb-4">
                <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Personal Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Full Name</label>
                        <p class="fw-medium">{{ $user->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="fw-medium">{{ $user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Phone</label>
                        <p class="fw-medium">{{ $user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Gender</label>
                        <p class="fw-medium">{{ $user->gender ?? 'N/A' }}</p>
                    </div>
<div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Department</label>
                        <p class="fw-medium">{{ $user->department->departmentname ?? 'Not assigned' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Joined</label>
                        <p class="fw-medium">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="form-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Permissions</h5>
                    <a href="{{ route('users.permissions', $user->userid) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-gear me-2"></i>Manage
                    </a>
                </div>
                <div class="row" id="permissionsList">
                    <!-- Permissions loaded via AJAX -->
                </div>
            </div>

            <!-- Activity Stats -->
            <div class="form-card">
                <h5 class="mb-3"><i class="bi bi-activity me-2"></i>Activity Summary</h5>
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stats-number text-primary">{{ $userStats['proposals'] ?? 0 }}</div>
                        <div class="stats-label">Proposals</div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-number text-success">{{ $userStats['approved'] ?? 0 }}</div>
                        <div class="stats-label">Approved</div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-number text-warning">{{ $userStats['pending'] ?? 0 }}</div>
                        <div class="stats-label">Pending</div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-number text-info">{{ $userStats['projects'] ?? 0 }}</div>
                        <div class="stats-label">Projects</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" class="ajax-form">
                @csrf
                <div class="modal-body">
<div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="fullname" value="{{ $user->name }}">
                    </div>
<div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phonenumber" value="{{ $user->phonenumber }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PF Number</label>
                        <input type="text" class="form-control" name="pfno" value="{{ $user->pfno }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
<div class="mb-3">
                        <label class="form-label">Department</label>
                        <select class="form-select" name="departmentidfk">
                            <option value="">Select Department</option>
                            @foreach($departments ?? [] as $dept)
                                <option value="{{ $dept->departmentid }}" 
                                    {{ $user->departmentidfk == $dept->departmentid ? 'selected' : '' }}>
                                    {{ $dept->departmentname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadPermissions();
    
    document.getElementById('editUserForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const formData = new FormData(this);
            const userData = Object.fromEntries(formData);
            
            await API.updateUser({{ $user->userid }}, userData);
            ARGPortal.showSuccess('User updated successfully');
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
            setTimeout(() => location.reload(), 1500);
        } catch (error) {
            ARGPortal.showError('Failed to update user');
        }
    });
    
    function loadPermissions() {
        // Load user permissions
        const permissions = [
            'canviewadmindashboard',
            'canviewproposals', 
            'canviewallproposals',
            'canmanageusers',
            'canmanagegrants'
        ];
        
        const permissionsList = $('#permissionsList');
        permissions.forEach(function(perm) {
            const hasPermission = {{ $user->haspermission('canviewadmindashboard') ? 'true' : 'false' }};
            const badge = hasPermission ? 'bg-success' : 'bg-secondary';
            const icon = hasPermission ? 'check-circle' : 'x-circle';
            
            permissionsList.append(`
                <div class="col-md-6 mb-2">
                    <span class="badge ${badge}">
                        <i class="bi bi-${icon} me-1"></i>${perm.replace('can', '').replace(/([A-Z])/g, ' $1')}
                    </span>
                </div>
            `);
        });
    }
    
    window.resetPassword = async function() {
        if (!confirm('Reset password for this user?')) return;
        
        try {
            await fetch('/api/users/{{ $user->userid }}/reset-password', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
            ARGPortal.showSuccess('Password reset successfully');
        } catch (error) {
            ARGPortal.showError('Failed to reset password');
        }
    };
});
</script>
@endpush
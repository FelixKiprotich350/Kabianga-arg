@extends('layouts.app')

@section('title', 'My Profile - UoK ARG Portal')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My Profile</h2>
            <p class="text-muted">Manage your account information and settings</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-2">{{ Auth::user()->email }}</p>
                    <small class="text-muted">Member since {{ Auth::user()->created_at->format('M Y') }}</small>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                                <i class="bi bi-person me-2"></i>Basic Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                                <i class="bi bi-shield-lock me-2"></i>Security
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="profileTabsContent">
                        <!-- Basic Details Tab -->
                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                            <form id="basicDetailsForm" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" value="{{ Auth::user()->name }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PF Number</label>
                                        <input type="text" class="form-control" id="pfno" name="pfno" value="{{ Auth::user()->pfno }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phonenumber" name="phonenumber" value="{{ Auth::user()->phonenumber }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Registration Date</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->created_at->format('M d, Y') }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Account Status</label>
                                        <input type="text" class="form-control" value="Active" readonly>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary" id="editProfileBtn">
                                        <i class="bi bi-pencil me-2"></i>Edit Profile
                                    </button>
                                    <button type="button" class="btn btn-success d-none" id="updateProfileBtn">
                                        <i class="bi bi-check-circle me-2"></i>Update Profile
                                    </button>
                                    <button type="button" class="btn btn-secondary d-none" id="cancelEditBtn">
                                        <i class="bi bi-x-circle me-2"></i>Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <form id="passwordForm" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="new_password" required>
                                    <div class="form-text">Password must be at least 8 characters long</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="new_password_confirmation" required>
                                </div>
                                
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-shield-check me-2"></i>Change Password
                                </button>
                            </form>
                            
                            <hr class="my-4">
                            
                            <div class="alert alert-info">
                                <h6><i class="bi bi-info-circle me-2"></i>Security Tips</h6>
                                <ul class="mb-0">
                                    <li>Use a strong, unique password</li>
                                    <li>Don't share your login credentials</li>
                                    <li>Log out when using shared computers</li>
                                    <li>Report any suspicious activity</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.getElementById('editProfileBtn');
    const updateBtn = document.getElementById('updateProfileBtn');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const form = document.getElementById('basicDetailsForm');
    
    const editableFields = ['fullname', 'email', 'phonenumber', 'pfno'];
    const originalValues = {};
    
    // Store original values
    editableFields.forEach(field => {
        originalValues[field] = document.getElementById(field).value;
    });
    
    editBtn.addEventListener('click', function() {
        // Enable editing
        editableFields.forEach(field => {
            document.getElementById(field).removeAttribute('readonly');
        });
        
        // Toggle buttons
        editBtn.classList.add('d-none');
        updateBtn.classList.remove('d-none');
        cancelBtn.classList.remove('d-none');
    });
    
    cancelBtn.addEventListener('click', function() {
        // Restore original values
        editableFields.forEach(field => {
            document.getElementById(field).value = originalValues[field];
            document.getElementById(field).setAttribute('readonly', true);
        });
        
        // Toggle buttons
        editBtn.classList.remove('d-none');
        updateBtn.classList.add('d-none');
        cancelBtn.classList.add('d-none');
    });
    
    updateBtn.addEventListener('click', function() {
        const formData = new FormData(form);
        const userId = "{{ Auth::user()->userid }}";
        const updateUrl = `/api/v1/users/${userId}`;
        
        fetch(updateUrl, {
            method: 'PUT',
            body: JSON.stringify(Object.fromEntries(formData)),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.type === 'success') {
                // Update original values
                editableFields.forEach(field => {
                    originalValues[field] = document.getElementById(field).value;
                    document.getElementById(field).setAttribute('readonly', true);
                });
                
                // Toggle buttons
                editBtn.classList.remove('d-none');
                updateBtn.classList.add('d-none');
                cancelBtn.classList.add('d-none');
                
                // Show success message
                showAlert('Profile updated successfully!', 'success');
            } else {
                showAlert(data.message || 'Update failed', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while updating profile', 'danger');
        });
    });
    
    // Password form handler
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newPassword = this.new_password.value;
        const confirmPassword = this.new_password_confirmation.value;
        
        if (newPassword !== confirmPassword) {
            showAlert('Passwords do not match', 'danger');
            return;
        }
        
        if (newPassword.length < 8) {
            showAlert('Password must be at least 8 characters long', 'danger');
            return;
        }
        
        // Here you would typically send the password change request
        showAlert('Password change functionality will be implemented', 'info');
    });
    
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
});
</script>
@endsection
@extends('layouts.app')

@section('title', 'My Profile - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">My Profile</h2>
            <p class="text-muted mb-0">Manage your account information and settings</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Profile Card -->
            <div class="form-card text-center">
                <div class="stats-icon primary mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="bi bi-person"></i>
                </div>
                <h4>{{ Auth::user()->name }}</h4>
                <p class="text-muted">{{ Auth::user()->email }}</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    @if(Auth::user()->email_verified_at)
                        <span class="badge bg-success">Verified</span>
                    @else
                        <span class="badge bg-warning">Unverified</span>
                    @endif
                    <span class="badge bg-info">{{ ucfirst(Auth::user()->role ?? 'User') }}</span>
                </div>
                <div class="d-grid">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-pencil me-2"></i>Edit Profile
                    </button>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="form-card">
                <h6 class="mb-3"><i class="bi bi-graph-up me-2"></i>My Statistics</h6>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stats-number text-primary" id="myProposals">0</div>
                        <div class="stats-label">Proposals</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stats-number text-success" id="myApproved">0</div>
                        <div class="stats-label">Approved</div>
                    </div>
                    <div class="col-6">
                        <div class="stats-number text-warning" id="myProjects">0</div>
                        <div class="stats-label">Projects</div>
                    </div>
                    <div class="col-6">
                        <div class="stats-number text-info" id="myFunding">0</div>
                        <div class="stats-label">Funding (K)</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="form-card mb-4">
                <h5 class="mb-3"><i class="bi bi-person-circle me-2"></i>Personal Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Full Name</label>
                        <p class="fw-medium">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email Address</label>
                        <p class="fw-medium">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Phone Number</label>
                        <p class="fw-medium">{{ Auth::user()->phonenumber ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">PF Number</label>
                        <p class="fw-medium">{{ Auth::user()->pfno ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Gender</label>
                        <p class="fw-medium">{{ Auth::user()->gender ?? 'Not specified' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Department</label>
                        <p class="fw-medium">{{ Auth::user()->department->departmentname ?? 'Not assigned' }}</p>
                    </div>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="form-card mb-4">
                <h5 class="mb-3"><i class="bi bi-gear me-2"></i>Account Settings</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Account Status</label>
                        <p class="fw-medium">
                            @if(Auth::user()->email_verified_at)
                                <span class="badge bg-success">Active & Verified</span>
                            @else
                                <span class="badge bg-warning">Pending Verification</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Member Since</label>
                        <p class="fw-medium">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Last Login</label>
                        <p class="fw-medium">{{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('M d, Y H:i') : 'Never' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Profile Updated</label>
                        <p class="fw-medium">{{ Auth::user()->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="bi bi-key me-2"></i>Change Password
                    </button>
                    @if(!Auth::user()->email_verified_at)
                    <button class="btn btn-outline-info" onclick="resendVerification()">
                        <i class="bi bi-envelope me-2"></i>Resend Verification
                    </button>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="form-card">
                <h5 class="mb-3"><i class="bi bi-clock-history me-2"></i>Recent Activity</h5>
                <div id="recentActivity">
                    <!-- Activity loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProfileForm" class="ajax-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="fullname" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" name="phonenumber" value="{{ Auth::user()->phonenumber }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PF Number</label>
                        <input type="text" class="form-control" name="pfno" value="{{ Auth::user()->pfno }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ Auth::user()->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ Auth::user()->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePasswordForm" class="ajax-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    loadUserStats();
    loadRecentActivity();
    
    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('api.users.updatebasicdetails', Auth::user()->userid) }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                ARGPortal.showSuccess('Profile updated successfully');
                $('#editProfileModal').modal('hide');
                setTimeout(() => location.reload(), 1500);
            },
            error: function() {
                ARGPortal.showError('Failed to update profile');
            }
        });
    });
    
    function loadUserStats() {
        // Mock stats - replace with actual API calls
        $('#myProposals').text('3');
        $('#myApproved').text('1');
        $('#myProjects').text('2');
        $('#myFunding').text('150K');
    }
    
    function loadRecentActivity() {
        const activities = [
            {
                icon: 'file-text',
                color: 'primary',
                text: 'Submitted new proposal',
                time: '2 hours ago'
            },
            {
                icon: 'check-circle',
                color: 'success',
                text: 'Proposal approved',
                time: '1 day ago'
            },
            {
                icon: 'person-check',
                color: 'info',
                text: 'Profile updated',
                time: '3 days ago'
            }
        ];
        
        const container = $('#recentActivity');
        
        activities.forEach(function(activity) {
            container.append(`
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
    
    window.resendVerification = function() {
        $.post("{{ route('verification.resend') }}", {
            _token: $('meta[name="csrf-token"]').attr('content')
        }).done(function() {
            ARGPortal.showSuccess('Verification email sent');
        }).fail(function() {
            ARGPortal.showError('Failed to send verification email');
        });
    };
});
</script>
@endpush
@extends('layouts.app')

@section('title', 'Dashboard - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Dashboard</h2>
            <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
        <div>
            <span class="badge bg-primary">{{ now()->format('M d, Y') }}</span>
        </div>
    </div>

    @if(Auth::user()->haspermission('canviewadmindashboard'))
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="bi bi-file-text"></i>
                </div>
                <div class="stats-number">{{ $allProposalscount ?? 0 }}</div>
                <div class="stats-label">Total Proposals</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-number">{{ $approvedProposalsCount ?? 0 }}</div>
                <div class="stats-label">Approved</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stats-number">{{ $pendingProposalsCount ?? 0 }}</div>
                <div class="stats-label">Pending Review</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon danger">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stats-number">{{ $rejectedProposalsCount ?? 0 }}</div>
                <div class="stats-label">Rejected</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Applications by Research Theme</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">All Themes</a></li>
                            <li><a class="dropdown-item" href="#">Health Sciences</a></li>
                            <li><a class="dropdown-item" href="#">Technology</a></li>
                            <li><a class="dropdown-item" href="#">Agriculture</a></li>
                        </ul>
                    </div>
                </div>
                <div style="height: 350px;">
                    <canvas id="themeChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="stats-card">
                <h5 class="mb-3">Recent Activities</h5>
                <div class="activity-list">
                    <div class="activity-item d-flex align-items-start mb-3">
                        <div class="activity-icon bg-success rounded-circle me-3">
                            <i class="bi bi-check text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 fw-medium">Proposal Approved</p>
                            <small class="text-muted">Dr. Smith's research proposal was approved</small>
                            <br><small class="text-muted">2 hours ago</small>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-start mb-3">
                        <div class="activity-icon bg-primary rounded-circle me-3">
                            <i class="bi bi-file-plus text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 fw-medium">New Submission</p>
                            <small class="text-muted">New proposal submitted for review</small>
                            <br><small class="text-muted">5 hours ago</small>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-start mb-3">
                        <div class="activity-icon bg-warning rounded-circle me-3">
                            <i class="bi bi-clock text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 fw-medium">Deadline Reminder</p>
                            <small class="text-muted">Grant application deadline in 3 days</small>
                            <br><small class="text-muted">1 day ago</small>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-sm btn-outline-primary">View All Activities</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="stats-card">
                <h5 class="mb-3">Quick Actions</h5>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('pages.proposals.viewnewproposal') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>New Proposal
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('pages.proposals.allproposals') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-files me-2"></i>View Proposals
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('pages.reports.home') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-graph-up me-2"></i>Reports
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('pages.users.manage') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-people me-2"></i>Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- User Dashboard -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="stats-card">
                <h5 class="mb-3">My Applications Status</h5>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="stats-number text-primary">3</div>
                        <div class="stats-label">Submitted</div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="stats-number text-success">1</div>
                        <div class="stats-label">Approved</div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="stats-number text-warning">2</div>
                        <div class="stats-label">Under Review</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="stats-card">
                <h5 class="mb-3">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('pages.proposals.viewnewproposal') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>New Application
                    </a>
                    <a href="{{ route('pages.proposals.myapplications') }}" class="btn btn-outline-primary">
                        <i class="bi bi-files me-2"></i>My Applications
                    </a>
                    <a href="{{ route('pages.projects.myprojects') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-kanban me-2"></i>My Projects
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    @if(Auth::user()->haspermission('canviewadmindashboard'))
    // Fetch and display chart data
    $.ajax({
        url: "{{ route('api.dashboard.chartdata') }}",
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            renderThemeChart(response);
        },
        error: function(xhr, status, error) {
            console.error('Error loading chart data:', error);
        }
    });

    function renderThemeChart(data) {
        const ctx = document.getElementById('themeChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endpush
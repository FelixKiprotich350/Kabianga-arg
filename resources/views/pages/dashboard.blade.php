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
    <div id="dashboard-stats" class="row mb-4">
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Loading statistics...</p>
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
                <div id="dashboard-chart" style="height: 350px;">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading chart...</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="stats-card">
                <h5 class="mb-3">Recent Activities</h5>
                <div id="recent-activity">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading activities...</p>
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
document.addEventListener('DOMContentLoaded', function() {
    PageLoaders.loadDashboardData();
});
</script>
@endpush
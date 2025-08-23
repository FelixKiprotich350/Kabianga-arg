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
                        <a href="{{ route('pages.proposals.index') }}" class="btn btn-outline-secondary w-100">
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
                    <div class="col-md-3 text-center">
                        <div class="stats-number text-primary">{{ $totalProposals ?? 0 }}</div>
                        <div class="stats-label">Total</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="stats-number text-success">{{ $approvedProposals ?? 0 }}</div>
                        <div class="stats-label">Approved</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="stats-number text-warning">{{ $pendingProposals ?? 0 }}</div>
                        <div class="stats-label">Pending</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="stats-number text-info">{{ $activeprojects ?? 0 }}</div>
                        <div class="stats-label">Active Projects</div>
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
                    <a href="{{ route('pages.proposals.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-files me-2"></i>My Applications
                    </a>
                    <a href="{{ route('pages.projects.index') }}" class="btn btn-outline-secondary">
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
    loadDashboardStats();
    loadDashboardChart();
    loadRecentActivity();
    @else
    // User dashboard is already populated from server-side data
    console.log('User dashboard loaded');
    @endif
});

function loadDashboardStats() {
    $.get('{{ route("api.dashboard.stats") }}')
        .done(data => {
            if (data.success) {
                renderDashboardStats(data.data);
            } else {
                $('#dashboard-stats').html('<div class="alert alert-warning">No statistics available</div>');
            }
        })
        .fail(() => {
            $('#dashboard-stats').html('<div class="alert alert-danger">Failed to load statistics</div>');
        });
}

function loadDashboardChart() {
    $.get('{{ route("api.dashboard.chartdata") }}')
        .done(data => {
            renderDashboardChart(data);
        })
        .fail(() => {
            $('#dashboard-chart').html('<div class="alert alert-danger">Failed to load chart data</div>');
        });
}

function loadRecentActivity() {
    $.get('{{ route("api.dashboard.recentactivity") }}')
        .done(data => {
            if (data.success) {
                renderRecentActivity(data.data);
            } else {
                $('#recent-activity').html('<div class="text-muted">No recent activity</div>');
            }
        })
        .fail(() => {
            $('#recent-activity').html('<div class="alert alert-danger">Failed to load recent activity</div>');
        });
}

function renderDashboardStats(stats) {
    $('#dashboard-stats').html(`
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="bi bi-files"></i>
                </div>
                <div class="stats-number">${stats.proposals.total}</div>
                <div class="stats-label">Total Proposals</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-number">${stats.proposals.approved}</div>
                <div class="stats-label">Approved</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stats-number">${stats.proposals.pending}</div>
                <div class="stats-label">Pending</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon danger">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stats-number">${stats.proposals.rejected}</div>
                <div class="stats-label">Rejected</div>
            </div>
        </div>
    `);
}

function renderDashboardChart(data) {
    if (!data || !data.labels) {
        $('#dashboard-chart').html('<div class="text-center py-4"><p class="text-muted">No chart data available</p></div>');
        return;
    }
    
    const ctx = document.createElement('canvas');
    $('#dashboard-chart').html(ctx);
    
    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function renderRecentActivity(activities) {
    if (!activities || activities.length === 0) {
        $('#recent-activity').html('<div class="text-muted">No recent activity</div>');
        return;
    }
    
    const html = activities.map(activity => `
        <div class="d-flex align-items-center mb-3">
            <div class="me-3">
                <i class="bi bi-${activity.type === 'proposal' ? 'file-text' : 'kanban'} text-primary"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-medium">${activity.title}</div>
                <small class="text-muted">${activity.user} • ${activity.date}</small>
            </div>
            <span class="badge bg-${activity.status === 'Approved' ? 'success' : activity.status === 'Pending' ? 'warning' : 'secondary'}">
                ${activity.status}
            </span>
        </div>
    `).join('');
    
    $('#recent-activity').html(html);
}
</script>
@endpush
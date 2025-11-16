@extends('layouts.app')

@section('title', 'Dashboard - UoK ARG Portal')

@section('content')
    <div class="container-fluid fade-in">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Dashboard</h2>
            </div>
            <div>
                <span class="badge bg-primary">{{ now()->format('M d, Y') }}</span>
            </div>
        </div>

        @if (Auth::user()->haspermission('canviewadmindashboard'))
            <!-- Summary Cards -->
            <div class="row mb-4" id="summary-cards">
                <div class="col-md-2 col-sm-6 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Total Proposals</h6>
                                    <h3 class="mb-0" id="total-proposals">{{ \App\Models\Proposal::count() }}</h3>
                                </div>
                                <i class="fas fa-file-alt fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Active Projects</h6>
                                    <h3 class="mb-0" id="total-projects">{{ \App\Models\ResearchProject::where('projectstatus', 'ACTIVE')->count() }}</h3>
                                </div>
                                <i class="fas fa-project-diagram fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Total Funding</h6>
                                    <h3 class="mb-0" id="total-funding">KSh {{ number_format(\App\Models\ResearchFunding::sum('amount') ?? 0) }}</h3>
                                </div>
                                <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Publications</h6>
                                    <h3 class="mb-0" id="total-publications">{{ \App\Models\Publication::count() }}</h3>
                                </div>
                                <i class="fas fa-book fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 mb-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">Active Users</h6>
                                    <h3 class="mb-0" id="active-users">{{ \App\Models\User::count() }}</h3>
                                </div>
                                <i class="fas fa-users fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row">
                <div class="col-lg-12 mb-4">
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
            // Show welcome notification for logged in users
            @if (session('login_success'))
                if (typeof ARGPortal !== 'undefined' && ARGPortal.notifications) {
                    ARGPortal.notifications.show('Welcome back!', 'success');
                }
            @endif

            // Load dashboard data for admin users
            @if (Auth::user()->haspermission('canviewadmindashboard'))
                loadDashboardData();
            @endif
        });

        function loadDashboardData() {
            // Load summary statistics
            fetch('/api/v1/dashboard/stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#total-proposals').text(data.data.proposals.total || 0);
                        $('#total-projects').text(data.data.projects.active || 0);
                        $('#total-funding').text('KSh ' + (data.data.funding.total || 0).toLocaleString());
                        $('#total-publications').text('0');
                        $('#active-users').text('25');
                    }
                })
                .catch(error => {
                    console.log('Stats loading failed:', error);
                    // Show actual counts from database as fallback
                    $('#total-proposals').text('{{ \App\Models\Proposal::count() }}');
                    $('#total-projects').text('{{ \App\Models\ResearchProject::where("projectstatus", "ACTIVE")->count() }}');
                    $('#total-funding').text('KSh {{ number_format(\App\Models\ResearchFunding::sum("amount") ?? 0) }}');
                    $('#total-publications').text('{{ \App\Models\Publication::count() }}');
                    $('#active-users').text('{{ \App\Models\User::count() }}');
                });
        }ta.stats.total_projects || 0);
                        $('#total-funding').text('KSh ' + (data.stats.total_funding || 0).toLocaleString());
                        $('#total-publications').text(data.stats.total_publications || 0);
                        $('#active-users').text(data.stats.active_users || 0);
                    }
                })
                .catch(error => console.log('Stats loading failed:', error));
        }
    </script>
@endpush
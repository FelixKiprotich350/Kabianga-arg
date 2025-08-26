@extends('layouts.app')

@section('title', 'Reports - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Reports & Analytics</h2>
            <p class="text-muted mb-0">Comprehensive reports and analysis dashboard</p>
        </div>
        <div>
            <button class="btn btn-outline-primary me-2" onclick="refreshReports()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <div class="dropdown d-inline-block">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="exportReport('proposals')">Proposals Report</a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportReport('projects')">Projects Report</a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportReport('financial')">Financial Report</a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportReport('publications')">Publications Report</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Report Tabs -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="proposals-tab" data-bs-toggle="tab" data-bs-target="#proposals" type="button" role="tab">
                        <i class="fas fa-file-alt me-2"></i>Proposals
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects" type="button" role="tab">
                        <i class="fas fa-project-diagram me-2"></i>Projects
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial" type="button" role="tab">
                        <i class="fas fa-chart-line me-2"></i>Financial
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                        <i class="fas fa-users me-2"></i>Users
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="publications-tab" data-bs-toggle="tab" data-bs-target="#publications" type="button" role="tab">
                        <i class="fas fa-book me-2"></i>Publications
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="reportTabsContent">
                <!-- Proposals Tab -->
                <div class="tab-pane fade show active" id="proposals" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="proposal-grant-filter">
                                <option value="all">All Grants</option>
                                @foreach($allgrants as $grant)
                                    <option value="{{$grant->grantid}}">{{$grant->grantid}} ({{$grant->finyear}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="proposal-theme-filter">
                                <option value="all">All Themes</option>
                                @foreach($allthemes as $theme)
                                    <option value="{{$theme->themeid}}">{{$theme->themename}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="proposal-department-filter">
                                <option value="all">All Departments</option>
                                @foreach($alldepartments as $dept)
                                    <option value="{{$dept->depid}}">{{$dept->shortname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" onclick="loadProposalsReport()">Apply Filters</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="proposalsBySchoolChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="proposalsByThemeChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Projects Tab -->
                <div class="tab-pane fade" id="projects" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="project-status-filter">
                                <option value="all">All Statuses</option>
                                <option value="ACTIVE">Active</option>
                                <option value="PAUSED">Paused</option>
                                <option value="COMPLETED">Completed</option>
                                <option value="CANCELLED">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="project-grant-filter">
                                <option value="all">All Grants</option>
                                @foreach($allgrants as $grant)
                                    <option value="{{$grant->grantid}}">{{$grant->grantid}} ({{$grant->finyear}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" onclick="loadProjectsReport()">Apply Filters</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="projectStatusChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="projectsByThemeChart"></canvas>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h5>Project Details</h5>
                        <div class="table-responsive">
                            <table class="table table-striped" id="projectsTable">
                                <thead>
                                    <tr>
                                        <th>Project Number</th>
                                        <th>Title</th>
                                        <th>Applicant</th>
                                        <th>Status</th>
                                        <th>Theme</th>
                                        <th>Grant</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Financial Tab -->
                <div class="tab-pane fade" id="financial" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="financial-grant-filter">
                                <option value="all">All Grants</option>
                                @foreach($allgrants as $grant)
                                    <option value="{{$grant->grantid}}">{{$grant->grantid}} ({{$grant->finyear}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="financial-year-filter">
                                <option value="all">All Years</option>
                                @foreach($allfinyears as $year)
                                    <option value="{{$year->finyear}}">{{$year->finyear}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" onclick="loadFinancialReport()">Apply Filters</button>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Total Funding</h5>
                                    <h3 class="text-success" id="financial-total">KES 0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Average Funding</h5>
                                    <h3 class="text-info" id="financial-average">KES 0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Funding Count</h5>
                                    <h3 class="text-primary" id="financial-count">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Budget Utilization</h5>
                                    <h3 class="text-warning" id="financial-utilization">0%</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <canvas id="fundingByMonthChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Users Tab -->
                <div class="tab-pane fade" id="users" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="user-department-filter">
                                <option value="all">All Departments</option>
                                @foreach($alldepartments as $dept)
                                    <option value="{{$dept->depid}}">{{$dept->shortname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="user-role-filter">
                                <option value="all">All Roles</option>
                                <option value="Researcher">Researcher</option>
                                <option value="Administrator">Administrator</option>
                                <option value="Committee Member">Committee Member</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" onclick="loadUsersReport()">Apply Filters</button>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Total Users</h5>
                                    <h3 class="text-primary" id="users-total">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Active Users</h5>
                                    <h3 class="text-success" id="users-active">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <canvas id="roleDistributionChart"></canvas>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th>Proposals</th>
                                    <th>Approved</th>
                                    <th>Success Rate</th>
                                    <th>Active Projects</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Publications Tab -->
                <div class="tab-pane fade" id="publications" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="publication-year-filter">
                                <option value="all">All Years</option>
                                @for($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{$year}}">{{$year}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="publication-theme-filter">
                                <option value="all">All Themes</option>
                                @foreach($allthemes as $theme)
                                    <option value="{{$theme->themeid}}">{{$theme->themename}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" onclick="loadPublicationsReport()">Apply Filters</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="publicationsByYearChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="publicationsByThemeChart"></canvas>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h5>Recent Publications</h5>
                        <div class="table-responsive">
                            <table class="table table-striped" id="publicationsTable">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Authors</th>
                                        <th>Year</th>
                                        <th>Publisher</th>
                                        <th>Theme</th>
                                        <th>Applicant</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/reports.js') }}"></script>
@endpush
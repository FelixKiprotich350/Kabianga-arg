@extends('layouts.app')

@section('title', 'Advanced Reports - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Advanced Reports</h2>
            <p class="text-muted mb-0">Progress tracking, compliance, and performance analytics</p>
        </div>
    </div>

    <!-- Report Tabs -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="advancedReportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="progress-tab" data-bs-toggle="tab" data-bs-target="#progress" type="button" role="tab">
                        <i class="fas fa-tasks me-2"></i>Progress Tracking
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="compliance-tab" data-bs-toggle="tab" data-bs-target="#compliance" type="button" role="tab">
                        <i class="fas fa-shield-alt me-2"></i>Compliance
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="performance-tab" data-bs-toggle="tab" data-bs-target="#performance" type="button" role="tab">
                        <i class="fas fa-chart-bar me-2"></i>Performance
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="budget-tab" data-bs-toggle="tab" data-bs-target="#budget" type="button" role="tab">
                        <i class="fas fa-calculator me-2"></i>Budget vs Actual
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="advancedReportTabsContent">
                <!-- Progress Tracking Tab -->
                <div class="tab-pane fade show active" id="progress" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="progress-status-filter">
                                <option value="all">All Statuses</option>
                                <option value="ACTIVE">Active</option>
                                <option value="PAUSED">Paused</option>
                                <option value="COMPLETED">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" onclick="loadProgressReport()">Load Report</button>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5>Overdue Projects</h5>
                                    <h2 id="overdue-count">0</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="progressTable">
                            <thead>
                                <tr>
                                    <th>Project Title</th>
                                    <th>Applicant</th>
                                    <th>Status</th>
                                    <th>Progress Reports</th>
                                    <th>Last Report</th>
                                    <th>Days Since Report</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Compliance Tab -->
                <div class="tab-pane fade" id="compliance" role="tabpanel">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h6>Missing Documents</h6>
                                    <h3 id="missing-docs">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h6>No Progress Reports</h6>
                                    <h3 id="no-progress">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6>Overdue Reports</h6>
                                    <h3 id="overdue-reports">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h6>Inactive Users</h6>
                                    <h3 id="inactive-users">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Tab -->
                <div class="tab-pane fade" id="performance" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="performance-year-filter">
                                <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                @for($year = date('Y')-1; $year >= 2020; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" onclick="loadPerformanceReport()">Load Report</button>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6>Approval Rate</h6>
                                    <h3 id="approval-rate">0%</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h6>Completion Rate</h6>
                                    <h3 id="completion-rate">0%</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6>Avg Processing Time</h6>
                                    <h3 id="avg-processing">0 days</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Budget vs Actual Tab -->
                <div class="tab-pane fade" id="budget" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="budget-grant-filter">
                                <option value="all">All Grants</option>
                                @foreach($allgrants ?? [] as $grant)
                                    <option value="{{$grant->grantid}}">{{$grant->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" onclick="loadBudgetReport()">Load Report</button>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h6>Total Budget</h6>
                                    <h3 id="total-budget">KES 0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6>Total Actual</h6>
                                    <h3 id="total-actual">KES 0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h6>Overall Variance</h6>
                                    <h3 id="overall-variance">KES 0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="budgetTable">
                            <thead>
                                <tr>
                                    <th>Project Title</th>
                                    <th>Grant</th>
                                    <th>Budget Amount</th>
                                    <th>Actual Funding</th>
                                    <th>Variance</th>
                                    <th>Variance %</th>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadProgressReport();
    loadComplianceReport();
    
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            const target = e.target.getAttribute('data-bs-target');
            switch(target) {
                case '#compliance':
                    loadComplianceReport();
                    break;
                case '#performance':
                    loadPerformanceReport();
                    break;
                case '#budget':
                    loadBudgetReport();
                    break;
            }
        });
    });
});

function loadProgressReport() {
    const status = document.getElementById('progress-status-filter').value;
    const params = new URLSearchParams({ status });
    
    fetch(`/api/v1/reports/progress?${params}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('overdue-count').textContent = data.overdue_projects;
            populateProgressTable(data.projects);
        })
        .catch(error => console.error('Error:', error));
}

function loadComplianceReport() {
    fetch('/api/v1/reports/compliance')
        .then(response => response.json())
        .then(data => {
            document.getElementById('missing-docs').textContent = data.proposals_missing_docs;
            document.getElementById('no-progress').textContent = data.projects_no_progress;
            document.getElementById('overdue-reports').textContent = data.overdue_reports;
            document.getElementById('inactive-users').textContent = data.inactive_users;
        })
        .catch(error => console.error('Error:', error));
}

function loadPerformanceReport() {
    const year = document.getElementById('performance-year-filter').value;
    const params = new URLSearchParams({ year });
    
    fetch(`/api/v1/reports/performance?${params}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('approval-rate').textContent = data.approval_rate + '%';
            document.getElementById('completion-rate').textContent = data.completion_rate + '%';
            document.getElementById('avg-processing').textContent = data.avg_processing_time + ' days';
        })
        .catch(error => console.error('Error:', error));
}

function loadBudgetReport() {
    const grant = document.getElementById('budget-grant-filter').value;
    const params = new URLSearchParams({ grant });
    
    fetch(`/api/v1/reports/budget-actual?${params}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-budget').textContent = 'KES ' + formatNumber(data.total_budget);
            document.getElementById('total-actual').textContent = 'KES ' + formatNumber(data.total_actual);
            document.getElementById('overall-variance').textContent = 'KES ' + formatNumber(data.overall_variance);
            populateBudgetTable(data.budget_analysis);
        })
        .catch(error => console.error('Error:', error));
}

function populateProgressTable(projects) {
    const tbody = document.querySelector('#progressTable tbody');
    tbody.innerHTML = '';
    
    projects.forEach(project => {
        const row = tbody.insertRow();
        const daysClass = project.days_since_report > 90 ? 'text-danger fw-bold' : '';
        row.innerHTML = `
            <td>${project.title}</td>
            <td>${project.applicant}</td>
            <td><span class="badge bg-${getStatusColor(project.status)}">${project.status}</span></td>
            <td>${project.progress_reports}</td>
            <td>${project.last_report_date}</td>
            <td class="${daysClass}">${project.days_since_report}</td>
        `;
    });
}

function populateBudgetTable(budgetData) {
    const tbody = document.querySelector('#budgetTable tbody');
    tbody.innerHTML = '';
    
    budgetData.forEach(item => {
        const row = tbody.insertRow();
        const varianceClass = item.variance >= 0 ? 'text-success' : 'text-danger';
        row.innerHTML = `
            <td>${item.title}</td>
            <td>${item.grant}</td>
            <td>KES ${formatNumber(item.budget_amount)}</td>
            <td>KES ${formatNumber(item.actual_funding)}</td>
            <td class="${varianceClass}">KES ${formatNumber(item.variance)}</td>
            <td class="${varianceClass}">${item.variance_percentage}%</td>
        `;
    });
}

function getStatusColor(status) {
    const colors = {
        'ACTIVE': 'success',
        'COMPLETED': 'primary',
        'PAUSED': 'warning',
        'CANCELLED': 'danger'
    };
    return colors[status] || 'secondary';
}

function formatNumber(num) {
    return new Intl.NumberFormat().format(num);
}
</script>
@endpush
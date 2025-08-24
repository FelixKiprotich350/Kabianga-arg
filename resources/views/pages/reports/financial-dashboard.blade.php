@extends('layouts.app')

@section('title', 'Financial Reports - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Financial Reports</h2>
            <p class="text-muted mb-0">Comprehensive financial analysis and funding reports</p>
        </div>
        <div>
            <button class="btn btn-outline-primary me-2" onclick="refreshFinancialData()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button class="btn btn-primary" onclick="exportFinancialReport()">
                <i class="fas fa-download"></i> Export PDF
            </button>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-0">Total Funding</h6>
                            <h3 class="mb-0" id="total-funding-amount">KES 0</h3>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-0">Average Funding</h6>
                            <h3 class="mb-0" id="avg-funding-amount">KES 0</h3>
                        </div>
                        <i class="fas fa-calculator fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-0">Budget Utilization</h6>
                            <h3 class="mb-0" id="budget-utilization">0%</h3>
                        </div>
                        <i class="fas fa-chart-pie fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title mb-0">Funding Count</h6>
                            <h3 class="mb-0" id="funding-count">0</h3>
                        </div>
                        <i class="fas fa-list-ol fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Grant</label>
                    <select class="form-select" id="grant-filter">
                        <option value="all">All Grants</option>
                        @foreach($allgrants as $grant)
                            <option value="{{$grant->grantid}}">{{$grant->grantid}} ({{$grant->finyear}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Financial Year</label>
                    <select class="form-select" id="year-filter">
                        <option value="all">All Years</option>
                        @foreach($allfinyears as $year)
                            <option value="{{$year->finyear}}">{{$year->finyear}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-primary d-block" onclick="applyFilters()">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Monthly Funding Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyFundingChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Funding Details Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Funding Details</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="fundingTable">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Applicant</th>
                            <th>Amount (KES)</th>
                            <th>Grant</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let monthlyChart;

document.addEventListener('DOMContentLoaded', function() {
    loadFinancialData();
});

function loadFinancialData() {
    const grantFilter = document.getElementById('grant-filter').value;
    const yearFilter = document.getElementById('year-filter').value;
    
    const params = new URLSearchParams({
        grant: grantFilter,
        year: yearFilter
    });
    
    fetch(`/api/v1/reports/financial?${params}`)
        .then(response => response.json())
        .then(data => {
            updateSummaryCards(data);
            updateMonthlyChart(data.funding_by_month);
        })
        .catch(error => {
            console.error('Error loading financial data:', error);
        });
}

function updateSummaryCards(data) {
    document.getElementById('total-funding-amount').textContent = 'KES ' + formatNumber(data.total_funding);
    document.getElementById('avg-funding-amount').textContent = 'KES ' + formatNumber(data.average_funding);
    document.getElementById('budget-utilization').textContent = data.budget_utilization + '%';
    document.getElementById('funding-count').textContent = data.funding_count;
}

function updateMonthlyChart(monthlyData) {
    const ctx = document.getElementById('monthlyFundingChart').getContext('2d');
    
    if (monthlyChart) {
        monthlyChart.destroy();
    }
    
    monthlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.labels,
            datasets: [{
                label: 'Funding Amount (KES)',
                data: monthlyData.data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Funding Distribution'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'KES ' + formatNumber(value);
                        }
                    }
                }
            }
        }
    });
}

function applyFilters() {
    loadFinancialData();
}

function refreshFinancialData() {
    loadFinancialData();
}

function exportFinancialReport() {
    const formData = new FormData();
    formData.append('type', 'financial');
    formData.append('grant', document.getElementById('grant-filter').value);
    formData.append('year', document.getElementById('year-filter').value);
    
    fetch('/api/v1/reports/export', {
        method: 'POST',
        body: formData
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = `financial_report_${new Date().toISOString().split('T')[0]}.pdf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        console.error('Export error:', error);
        alert('Failed to export report. Please try again.');
    });
}

function formatNumber(num) {
    return new Intl.NumberFormat().format(num);
}
</script>
@endpush
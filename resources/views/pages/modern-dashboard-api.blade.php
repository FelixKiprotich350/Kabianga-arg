@extends('layouts.app')

@section('title', 'Dashboard - UoK ARG Portal')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Dashboard</h1>
            <p class="text-muted">Welcome to the Annual Research Grants Portal</p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="PageLoaders.loadDashboardData()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div id="dashboard-stats" class="mb-4">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading dashboard statistics...</p>
        </div>
    </div>

    <!-- Charts and Activity Row -->
    <div class="row">
        <!-- Chart Section -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Analytics</h5>
                </div>
                <div class="card-body">
                    <div id="dashboard-chart">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading chart data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div id="recent-activity">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading activities...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="/proposals/newproposal" class="btn btn-outline-primary w-100 mb-2">
                                <i class="bi bi-plus-circle"></i> New Proposal
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/proposals/myapplications" class="btn btn-outline-success w-100 mb-2">
                                <i class="bi bi-file-text"></i> My Applications
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/projects/myprojects" class="btn btn-outline-info w-100 mb-2">
                                <i class="bi bi-briefcase"></i> My Projects
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/reports/home" class="btn btn-outline-warning w-100 mb-2">
                                <i class="bi bi-graph-up"></i> Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="dashboard-content" style="display: none;"></div>
@endsection

@push('styles')
<style>
.stats-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-2px);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
    font-size: 1.5rem;
}

.stats-content h3 {
    margin: 0;
    font-size: 2rem;
    font-weight: bold;
    color: #333;
}

.stats-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.chart-container {
    padding: 1rem;
}

.chart-bars {
    display: flex;
    align-items: end;
    gap: 1rem;
    height: 200px;
    padding: 1rem 0;
}

.chart-bar {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.bar {
    background: linear-gradient(45deg, #007bff, #0056b3);
    width: 100%;
    min-height: 20px;
    border-radius: 4px 4px 0 0;
    transition: all 0.3s ease;
}

.chart-bar:hover .bar {
    background: linear-gradient(45deg, #0056b3, #004085);
}

.activity-list {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: start;
    padding: 0.75rem 0;
    border-bottom: 1px solid #eee;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: #6c757d;
}

.activity-content {
    flex: 1;
}

.activity-content p {
    margin: 0 0 0.25rem 0;
    font-size: 0.9rem;
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@push('scripts')
<script>
// Page-specific initialization
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard data when page loads
    PageLoaders.loadDashboardData();
    
    // Set up auto-refresh every 5 minutes
    setInterval(() => {
        PageLoaders.loadDashboardData();
    }, 300000);
});
</script>
@endpush
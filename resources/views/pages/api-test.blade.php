@extends('layouts.app')

@section('title', 'API Test - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">API Test Page</h2>
            <p class="text-muted mb-0">Test API endpoints and debug issues</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>API Tests</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="testConnection()">Test Connection</button>
                        <button class="btn btn-secondary" onclick="testUsers()">Test Users API</button>
                        <button class="btn btn-info" onclick="testProposals()">Test Proposals API</button>
                        <button class="btn btn-success" onclick="testDashboard()">Test Dashboard API</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Results</h5>
                </div>
                <div class="card-body">
                    <pre id="results" style="max-height: 400px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px;">
Click a test button to see results...
                    </pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function logResult(title, data) {
    const results = document.getElementById('results');
    const timestamp = new Date().toLocaleTimeString();
    results.textContent += `\n[${timestamp}] ${title}:\n${JSON.stringify(data, null, 2)}\n${'='.repeat(50)}\n`;
    results.scrollTop = results.scrollHeight;
}

async function testConnection() {
    try {
        const response = await fetch('/api/v1/test/connection');
        const data = await response.json();
        logResult('Connection Test', data);
    } catch (error) {
        logResult('Connection Test Error', { error: error.message });
    }
}

async function testUsers() {
    try {
        const response = await API.getAllUsers();
        logResult('Users API Test', response);
    } catch (error) {
        logResult('Users API Test Error', { error: error.message });
    }
}

async function testProposals() {
    try {
        const response = await API.getAllProposals();
        logResult('Proposals API Test', response);
    } catch (error) {
        logResult('Proposals API Test Error', { error: error.message });
    }
}

async function testDashboard() {
    try {
        const response = await API.getDashboardStats();
        logResult('Dashboard API Test', response);
    } catch (error) {
        logResult('Dashboard API Test Error', { error: error.message });
    }
}
</script>
@endpush
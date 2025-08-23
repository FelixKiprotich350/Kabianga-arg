@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h2 class="mb-1">Welcome back, {{ Auth::user()->name }}!</h2>
                    <p class="mb-0">Here's an overview of your research activities</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-file-text text-primary" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ $totalProposals }}</h3>
                    <p class="text-muted mb-0">Total Proposals</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ $approvedProposals }}</h3>
                    <p class="text-muted mb-0">Approved</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ $pendingProposals }}</h3>
                    <p class="text-muted mb-0">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-diagram-3 text-info" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ $activeProjects }}</h3>
                    <p class="text-muted mb-0">Active Projects</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Overview -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-check2-all text-success" style="font-size: 1.5rem;"></i>
                    <h4 class="mt-2">{{ $completedProjects }}</h4>
                    <p class="text-muted mb-0">Completed Projects</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-x-circle text-danger" style="font-size: 1.5rem;"></i>
                    <h4 class="mt-2">{{ $cancelledProjects }}</h4>
                    <p class="text-muted mb-0">Cancelled Projects</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-cash-stack text-success" style="font-size: 1.5rem;"></i>
                    <h4 class="mt-2">{{ number_format($totalFunding) }}</h4>
                    <p class="text-muted mb-0">Total Funding</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Proposals</h5>
                </div>
                <div class="card-body">
                    @if($recentProposals->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentProposals as $proposal)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ Str::limit($proposal->researchtitle ?? 'Untitled', 50) }}</h6>
                                        <small class="text-muted">{{ $proposal->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <span class="badge 
                                        @if($proposal->approvalstatus == 'approved') bg-success
                                        @elseif($proposal->approvalstatus == 'pending') bg-warning
                                        @else bg-danger
                                        @endif">
                                        {{ ucfirst($proposal->approvalstatus) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No proposals yet</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Projects</h5>
                </div>
                <div class="card-body">
                    @if($recentProjects->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentProjects as $project)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $project->researchnumber ?? 'Project' }}</h6>
                                        <small class="text-muted">{{ $project->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <span class="badge 
                                        @if($project->projectstatus == 'Active') bg-success
                                        @elseif($project->projectstatus == 'Completed') bg-info
                                        @else bg-secondary
                                        @endif">
                                        {{ $project->projectstatus }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No projects yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(Auth::user()->haspermission('canmakenewproposal'))
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('pages.proposals.viewnewproposal') }}" class="btn btn-primary w-100">
                                    <i class="bi bi-plus-circle"></i> New Proposal
                                </a>
                            </div>
                        @endif
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('pages.proposals.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-files"></i> View Proposals
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('pages.projects.index') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-diagram-3"></i> View Projects
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('pages.myprofile') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-person-circle"></i> My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
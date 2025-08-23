@extends('layouts.app')

@section('title', 'New Proposal - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">New Research Proposal</h2>
            <p class="text-muted mb-0">Submit your research proposal for grant consideration</p>
        </div>
        <a href="{{ route('pages.proposals.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Applications
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('route.proposals.post') }}" method="POST" class="ajax-form">
                @csrf
                
                <!-- Basic Information -->
                <div class="form-card mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon primary me-3">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label fw-medium">Project Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="theme" class="form-label fw-medium">Research Theme *</label>
                            <select class="form-select" id="theme" name="theme_id" required>
                                <option value="">Select Theme</option>
                                @foreach($themes ?? [] as $theme)
                                    <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="grant" class="form-label fw-medium">Grant Type *</label>
                            <select class="form-select" id="grant" name="grant_id" required>
                                <option value="">Select Grant</option>
                                @foreach($grants ?? [] as $grant)
                                    <option value="{{ $grant->id }}">{{ $grant->name }} - KES {{ number_format($grant->amount) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="duration" class="form-label fw-medium">Project Duration (months) *</label>
                            <input type="number" class="form-control" id="duration" name="duration" min="1" max="36" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="abstract" class="form-label fw-medium">Abstract *</label>
                        <textarea class="form-control" id="abstract" name="abstract" rows="4" placeholder="Provide a brief summary of your research proposal..." required></textarea>
                        <div class="form-text">Maximum 500 words</div>
                    </div>
                </div>

                <!-- Research Details -->
                <div class="form-card mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon success me-3">
                            <i class="bi bi-search"></i>
                        </div>
                        <h5 class="mb-0">Research Details</h5>
                    </div>
                    
                    <div class="mb-3">
                        <label for="objectives" class="form-label fw-medium">Research Objectives *</label>
                        <textarea class="form-control" id="objectives" name="objectives" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="methodology" class="form-label fw-medium">Methodology *</label>
                        <textarea class="form-control" id="methodology" name="methodology" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="expected_outcomes" class="form-label fw-medium">Expected Outcomes *</label>
                        <textarea class="form-control" id="expected_outcomes" name="expected_outcomes" rows="3" required></textarea>
                    </div>
                </div>

                <!-- Budget Information -->
                <div class="form-card mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon warning me-3">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h5 class="mb-0">Budget Information</h5>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="total_budget" class="form-label fw-medium">Total Budget (KES) *</label>
                            <input type="number" class="form-control" id="total_budget" name="total_budget" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="requested_amount" class="form-label fw-medium">Requested Amount (KES) *</label>
                            <input type="number" class="form-control" id="requested_amount" name="requested_amount" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="budget_justification" class="form-label fw-medium">Budget Justification *</label>
                        <textarea class="form-control" id="budget_justification" name="budget_justification" rows="3" required></textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Submit Proposal
                    </button>
                </div>
            </form>
        </div>
        
        <div class="col-lg-4">
            <!-- Guidelines -->
            <div class="form-card mb-4">
                <h6 class="mb-3"><i class="bi bi-lightbulb me-2"></i>Submission Guidelines</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Ensure all required fields are completed</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Abstract should be clear and concise</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Budget must be realistic and justified</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Review all information before submission</li>
                </ul>
            </div>
            
            <!-- Important Dates -->
            <div class="form-card">
                <h6 class="mb-3"><i class="bi bi-calendar me-2"></i>Important Dates</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span>Submission Deadline:</span>
                    <strong class="text-danger">Dec 31, 2024</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Review Period:</span>
                    <span>Jan 1-15, 2025</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Results Announcement:</span>
                    <span>Jan 20, 2025</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Character count for abstract
    $('#abstract').on('input', function() {
        const maxLength = 500;
        const currentLength = $(this).val().split(' ').length;
        const remaining = maxLength - currentLength;
        
        $(this).next('.form-text').text(`${currentLength}/${maxLength} words`);
        
        if (remaining < 0) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Budget validation
    $('#requested_amount, #total_budget').on('input', function() {
        const requested = parseFloat($('#requested_amount').val()) || 0;
        const total = parseFloat($('#total_budget').val()) || 0;
        
        if (requested > total && total > 0) {
            $('#requested_amount').addClass('is-invalid');
            ARGPortal.showError('Requested amount cannot exceed total budget');
        } else {
            $('#requested_amount').removeClass('is-invalid');
        }
    });
});
</script>
@endpush
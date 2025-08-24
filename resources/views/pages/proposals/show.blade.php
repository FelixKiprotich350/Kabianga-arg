@extends('layouts.app')

@section('title', 'Proposal Details - UoK ARG Portal')

@section('content')
    <div class="container-fluid fade-in">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">{{ $prop->researchtitle ?? 'Proposal Details' }}</h2>
                <p class="text-muted mb-0">ID: {{ $prop->proposalcode ?? $prop->proposalid }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="/api/v1/proposals/{{ $prop->proposalid }}/pdf" class="btn btn-outline-secondary" target="_blank">
                    <i class="bi bi-download me-2"></i>Download PDF
                </a>
                @if (Auth::user()->userid == $prop->useridfk && $prop->caneditstatus)
                    <a href="{{ route('pages.proposals.editproposal', $prop->proposalid) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                @endif
                <a href="{{ route('pages.proposals.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <!-- Basic Information -->
                <div class="form-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                        <span
                            class="badge {{ ($prop->approvalstatus && $prop->approvalstatus->value == 'APPROVED') ? 'bg-success' : (($prop->approvalstatus && $prop->approvalstatus->value == 'REJECTED') ? 'bg-danger' : 'bg-warning') }}">
                            {{ $prop->approvalstatus ? $prop->approvalstatus->value : 'PENDING' }}
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Research Title</label>
                            <p class="fw-medium">{{ $prop->researchtitle ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Research Theme</label>
                            <p class="fw-medium">{{ $prop->themeitem->themename ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Grant Type</label>
                            <p class="fw-medium">{{ $prop->grantitem->grantname ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Department</label>
                            <p class="fw-medium">{{ $prop->department->departmentname ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Commencing Date</label>
                            <p class="fw-medium">
                                {{ $prop->commencingdate ? date('M d, Y', strtotime($prop->commencingdate)) : 'Not set' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Termination Date</label>
                            <p class="fw-medium">
                                {{ $prop->terminationdate ? date('M d, Y', strtotime($prop->terminationdate)) : 'Not set' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Research Details -->
                <div class="form-card mb-4">
                    <h5 class="mb-3"><i class="bi bi-search me-2"></i>Research Details</h5>
                    <div class="mb-3">
                        <label class="form-label text-muted">Objectives</label>
                        <p class="fw-medium">{{ $prop->objectives ?? 'Not provided' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Hypothesis</label>
                        <p class="fw-medium">{{ $prop->hypothesis ?? 'Not provided' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Significance</label>
                        <p class="fw-medium">{{ $prop->significance ?? 'Not provided' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Expected Output</label>
                        <p class="fw-medium">{{ $prop->expoutput ?? 'Not provided' }}</p>
                    </div>
                </div>

                <!-- Collaborators -->
                <div class="form-card mb-4">
                    <h5 class="mb-3"><i class="bi bi-people me-2"></i>Collaborators</h5>
                    <div id="collaboratorsList">
                        <!-- Loaded via AJAX -->
                    </div>
                </div>

                <!-- Budget -->
                <div class="form-card">
                    <h5 class="mb-3"><i class="bi bi-cash-stack me-2"></i>Budget Breakdown</h5>
                    <div id="budgetBreakdown">
                        <!-- Loaded via AJAX -->
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Applicant Info -->
                <div class="form-card mb-4">
                    <h6 class="mb-3"><i class="bi bi-person me-2"></i>Applicant Information</h6>
                    <div class="text-center mb-3">
                        <div class="stats-icon primary mx-auto mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <i class="bi bi-person"></i>
                        </div>
                        <h6>{{ $prop->applicant->name ?? 'N/A' }}</h6>
                        <p class="text-muted small">{{ $prop->applicant->email ?? 'N/A' }}</p>
                    </div>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phone:</span>
                            <span>{{ $prop->cellphone ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Office:</span>
                            <span>{{ $prop->officephone ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Qualification:</span>
                            <span>{{ $prop->highqualification ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="form-card mb-4">
                    <h6 class="mb-3"><i class="bi bi-clock-history me-2"></i>Status Timeline</h6>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="small mb-1">Created</h6>
                                <p class="small text-muted">
                                    {{ $prop->created_at ? $prop->created_at->format('M d, Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                        @if ($prop->submittedstatus && $prop->submittedstatus->value == 'SUBMITTED')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="small mb-1">Submitted</h6>
                                    <p class="small text-muted">
                                        {{ $prop->updated_at ? $prop->updated_at->format('M d, Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                        @endif
                        @if ($prop->approvalstatus && $prop->approvalstatus->value != 'PENDING')
                            <div class="timeline-item">
                                <div
                                    class="timeline-marker {{ ($prop->approvalstatus && $prop->approvalstatus->value == 'APPROVED') ? 'bg-success' : 'bg-danger' }}">
                                </div>
                                <div class="timeline-content">
                                    <h6 class="small mb-1">{{ $prop->approvalstatus ? $prop->approvalstatus->value : 'PENDING' }}</h6>
                                    <p class="small text-muted">
                                        {{ $prop->updated_at ? $prop->updated_at->format('M d, Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if (Auth::user()->haspermission('canapproveproposal') && $prop->approvalstatus && $prop->approvalstatus->value == 'PENDING')
                    <div class="form-card">
                        <h6 class="mb-3"><i class="bi bi-gear me-2"></i>Actions</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-success" onclick="approveProposal()">
                                <i class="bi bi-check-circle me-2"></i>Approve
                            </button>
                            <button class="btn btn-danger" onclick="rejectProposal()">
                                <i class="bi bi-x-circle me-2"></i>Reject
                            </button>
                            <button class="btn btn-outline-secondary" onclick="markAsDraft()">
                                <i class="bi bi-file-earmark me-2"></i>Mark as Draft
                            </button>
                            <button class="btn btn-outline-warning" onclick="requestChanges()">
                                <i class="bi bi-pencil me-2"></i>Request Changes
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 1.5rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1rem;
        }

        .timeline-marker {
            position: absolute;
            left: -1.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .timeline-content {
            margin-left: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Store funding years data for modal
            window.finyears = @json($finyears);
            
            loadCollaborators();
            loadBudget();

            function loadCollaborators() {
                $.ajax({
                    url: "/api/v1/proposals/{{ $prop->proposalid }}/collaborators",
                    type: 'GET',
                    success: function(response) {
                        const container = $('#collaboratorsList');
                        if (response.length === 0) {
                            container.html('<p class="text-muted">No collaborators added</p>');
                            return;
                        }

                        response.forEach(function(collab) {
                            container.append(`
                        <div class="d-flex align-items-center mb-2">
                            <div class="stats-icon success me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div>
                                <div class="fw-medium">${collab.collaboratorname || 'N/A'}</div>
                                <small class="text-muted">${collab.institution || 'N/A'}</small>
                            </div>
                        </div>
                    `);
                        });
                    },
                    error: function(xhr, status, error) {
                        $('#collaboratorsList').html('<p class="text-muted">Unable to load collaborators</p>');
                        console.error('Error loading collaborators:', error);
                    }
                });
            }

            function loadBudget() {
                $.ajax({
                    url: "/api/v1/proposals/{{ $prop->proposalid }}/expenditures",
                    type: 'GET',
                    success: function(response) {
                        const container = $('#budgetBreakdown');
                        const summary = response.summary;

                        if (!summary) {
                            container.html('<p class="text-muted">No budget information available</p>');
                            return;
                        }

                        container.html(`
                    <div class="row text-center">
                        <div class="col-6 mb-2">
                            <div class="stats-number text-primary">${summary.totalFacilities || 0}</div>
                            <div class="stats-label">Facilities</div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="stats-number text-success">${summary.totalConsumables || 0}</div>
                            <div class="stats-label">Consumables</div>
                        </div>
                        <div class="col-6">
                            <div class="stats-number text-warning">${summary.totalTravels || 0}</div>
                            <div class="stats-label">Travel</div>
                        </div>
                        <div class="col-6">
                            <div class="stats-number text-info">${summary.totalOthers || 0}</div>
                            <div class="stats-label">Others</div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total Budget:</strong>
                        <strong>KES ${(summary.totalExpenditures || 0).toLocaleString()}</strong>
                    </div>
                `);
                    },
                    error: function(xhr, status, error) {
                        $('#budgetBreakdown').html('<p class="text-muted">Unable to load budget information</p>');
                        console.error('Error loading budget:', error);
                    }
                });
            }





            window.submitApproval = function() {
                console.log('submitApproval called');
                const fundingYear = $('#approvalModal select[name="fundingfinyearfk"]').val();
                const comment = $('#approvalModal textarea[name="comment"]').val();
                
                console.log('Modal exists:', $('#approvalModal').length);
                console.log('Select exists:', $('#approvalModal select[name="fundingfinyearfk"]').length);
                console.log('Funding Year Value:', fundingYear);
                console.log('Comment Value:', comment);
                
                if (!fundingYear || fundingYear === '' || fundingYear === 'undefined') {
                    ARGPortal.showError('Please select a funding year');
                    return;
                }
                
                const formData = {
                    fundingfinyearfk: parseInt(fundingYear),
                    comment: comment || ''
                };

                $.ajax({
                    url: `/api/v1/proposals/{{ $prop->proposalid }}/approve`,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#approvalModal').modal('hide');
                        ARGPortal.showSuccess(response.message);
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message || 'Failed to approve proposal';
                        ARGPortal.showError(error);
                    }
                });
            };

            window.submitRejection = function() {
                const comment = $('#rejectionModal textarea[name="comment"]').val();

                if (!comment.trim()) {
                    ARGPortal.showError('Please provide a rejection reason');
                    return;
                }

                $.ajax({
                    url: `/api/v1/proposals/{{ $prop->proposalid }}/reject`,
                    type: 'POST',
                    data: { comment: comment },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#rejectionModal').modal('hide');
                        ARGPortal.showSuccess(response.message);
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message || 'Failed to reject proposal';
                        ARGPortal.showError(error);
                    }
                });
            };

            window.submitChanges = function() {
                const comment = $('#changesModal textarea[name="comment"]').val();

                if (!comment.trim()) {
                    ARGPortal.showError('Please describe the changes needed');
                    return;
                }

                $.ajax({
                    url: `/api/v1/proposals/{{ $prop->proposalid }}/request-changes`,
                    type: 'POST',
                    data: { comment: comment },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#changesModal').modal('hide');
                        ARGPortal.showSuccess(response.message);
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message || 'Failed to request changes';
                        ARGPortal.showError(error);
                    }
                });
            };
        });

        // Move modal functions to global scope
        window.showApprovalModal = function() {
            console.log('showApprovalModal called');
            $.ajax({
                url: '/api/v1/financial-years',
                type: 'GET',
                success: function(finyears) {
                    let finyearOptions = '<option value="">Select Funding Year</option>';
                    if (finyears && finyears.length > 0) {
                        finyears.forEach(function(year) {
                            finyearOptions += `<option value="${year.finyearid}">${year.finyear}</option>`;
                        });
                    }
                    showModal(finyearOptions);
                },
                error: function() {
                    const finyearOptions = '<option value="">Select Funding Year</option><option value="1">2024/2025</option><option value="2">2025/2026</option>';
                    showModal(finyearOptions);
                }
            });
        };

        function showModal(finyearOptions) {
            const modal = $(`
                <div class="modal fade" id="approvalModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Approve Proposal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="approvalForm">
                                    <div class="mb-3">
                                        <label class="form-label">Funding Year *</label>
                                        <select class="form-select" name="fundingfinyearfk" required>
                                            ${finyearOptions}
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Comment (Optional)</label>
                                        <textarea class="form-control" name="comment" rows="3" placeholder="Add approval comment..."></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-success" onclick="submitApproval()">Approve</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            $('body').append(modal);
            $('#approvalModal').modal('show');
            $('#approvalModal').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        }

        window.showRejectionModal = function() {
            const modal = $(`
                <div class="modal fade" id="rejectionModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Reject Proposal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="rejectionForm">
                                    <div class="mb-3">
                                        <label class="form-label">Rejection Reason *</label>
                                        <textarea class="form-control" name="comment" rows="4" placeholder="Please provide reason for rejection..." required></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" onclick="submitRejection()">Reject</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            $('body').append(modal);
            $('#rejectionModal').modal('show');
            $('#rejectionModal').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        };

        window.showRequestChangesModal = function() {
            const modal = $(`
                <div class="modal fade" id="changesModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Request Changes</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="changesForm">
                                    <div class="mb-3">
                                        <label class="form-label">Changes Required *</label>
                                        <textarea class="form-control" name="comment" rows="4" placeholder="Describe the changes needed..." required></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-warning" onclick="submitChanges()">Request Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            $('body').append(modal);
            $('#changesModal').modal('show');
            $('#changesModal').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        };

        // Global functions for onclick handlers
        window.approveProposal = function() {
            console.log('approveProposal called');
            try {
                showApprovalModal();
            } catch(e) {
                console.error('Error in approveProposal:', e);
            }
        };

        window.rejectProposal = function() {
            showRejectionModal();
        };

        window.markAsDraft = function() {
            if (confirm('Mark this proposal as draft? This will allow the applicant to edit it.')) {
                $.ajax({
                    url: `/api/v1/proposals/{{ $prop->proposalid }}/mark-draft`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        ARGPortal.showSuccess(response.message);
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message || 'Failed to mark as draft';
                        ARGPortal.showError(error);
                    }
                });
            }
        };

        window.requestChanges = function() {
            showRequestChangesModal();
        };
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Financial Years - UoK ARG Portal')

@section('content')
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Financial Years</h2>
            <p class="text-muted mb-0">Manage financial years for grants</p>
        </div>
        @if(auth()->user()->haspermission('canaddoreditfinyear'))
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFinYearModal">
            <i class="bi bi-plus-circle me-2"></i>Add Financial Year
        </button>
        @endif
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="bi bi-calendar-range"></i>
                </div>
                <div class="stats-number" id="totalFinYears">0</div>
                <div class="stats-label">Total Financial Years</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stats-number" id="currentFinYear">Current Year</div>
                <div class="stats-label">Active Financial Year</div>
            </div>
        </div>
    </div>

    <!-- Financial Years Table -->
    <div class="form-card">
        <div class="table-responsive">
            <table class="table table-hover" id="finYearsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Financial Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Financial Year Modal -->
<div class="modal fade" id="addFinYearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Financial Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addFinYearForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Financial Year *</label>
                        <input type="text" class="form-control" name="finyear" placeholder="e.g., 2024/2025" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date *</label>
                                <input type="date" class="form-control" name="startdate" id="startDate" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date *</label>
                                <input type="date" class="form-control" name="enddate" id="endDate" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="Optional description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Financial Year</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    loadFinYears();
    
    // Date validation
    $('#startDate, #endDate').on('change', function() {
        const startDate = new Date($('#startDate').val());
        const endDate = new Date($('#endDate').val());
        
        if (startDate && endDate && startDate >= endDate) {
            ARGPortal.showError('Start date must be before end date');
            $(this).val('');
        }
    });

    // Add financial year form
    $('#addFinYearForm').on('submit', function(e) {
        e.preventDefault();
        $.post("{{ route('api.finyear.post') }}", $(this).serialize())
            .done(() => {
                ARGPortal.showSuccess('Financial year added successfully');
                $('#addFinYearModal').modal('hide');
                this.reset();
                loadFinYears();
            })
            .fail(xhr => {
                const response = xhr.responseJSON;
                ARGPortal.showError(response?.message || 'Failed to add financial year');
            });
    });

    function loadFinYears() {
        $.get("{{ route('api.finyear.fetchallfinyears') }}")
            .done(data => {
                populateTable(data);
                updateStats(data);
            })
            .fail(() => ARGPortal.showError('Failed to load financial years'));
    }

    function populateTable(data) {
        const tbody = $('#finYearsTable tbody');
        tbody.empty();
        
        if (data.length === 0) {
            tbody.append('<tr><td colspan="6" class="text-center text-muted">No financial years found</td></tr>');
            return;
        }

        data.forEach(year => {
            tbody.append(`
                <tr>
                    <td>${year.id}</td>
                    <td><strong>${year.finyear}</strong></td>
                    <td>${year.startdate}</td>
                    <td>${year.enddate}</td>
                    <td>${year.description || 'N/A'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="viewFinYear(${year.id})">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function updateStats(data) {
        $('#totalFinYears').text(data.length);
        
        // Find current year (you might want to adjust this logic)
        const currentYear = data.find(year => {
            const now = new Date();
            const start = new Date(year.startdate);
            const end = new Date(year.enddate);
            return now >= start && now <= end;
        });
        
        $('#currentFinYear').text(currentYear ? currentYear.finyear : 'None Active');
    }
});

function viewFinYear(id) {
    // Implement view functionality if needed
    console.log('View financial year:', id);
}
</script>
@endpush
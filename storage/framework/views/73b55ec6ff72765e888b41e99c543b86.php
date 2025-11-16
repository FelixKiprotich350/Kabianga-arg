<?php $__env->startSection('title', 'Grants Management - UoK ARG Portal'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Grants & Financial Years</h2>
            <p class="text-muted mb-0">Manage research grants and financial years</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="managementTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="grants-tab" data-bs-toggle="tab" data-bs-target="#grants" type="button" role="tab">
                <i class="bi bi-cash-stack me-2"></i>Grants
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="finyears-tab" data-bs-toggle="tab" data-bs-target="#finyears" type="button" role="tab">
                <i class="bi bi-calendar-range me-2"></i>Financial Years
            </button>
        </li>
        <?php if(auth()->user()->haspermission('canupdatecurrentgrantandyear')): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                <i class="bi bi-gear me-2"></i>Settings
            </button>
        </li>
        <?php endif; ?>
    </ul>

    <div class="tab-content" id="managementTabsContent">
        <!-- Grants Tab -->
        <div class="tab-pane fade show active" id="grants" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Grants Management</h4>
                <?php if(auth()->user()->haspermission('canaddoreditgrant')): ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGrantModal">
                    <i class="bi bi-plus-circle me-2"></i>Add Grant
                </button>
                <?php endif; ?>
            </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stats-number" id="totalGrants"><?php echo e(count($allgrants)); ?></div>
                <div class="stats-label">Total Grants</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-number" id="openGrants"><?php echo e($allgrants->where('status', 'Open')->count()); ?></div>
                <div class="stats-label">Open Grants</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="bi bi-calendar-range"></i>
                </div>
                <div class="stats-number" id="totalFinYears"><?php echo e(count($finyears)); ?></div>
                <div class="stats-label">Financial Years</div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="form-card mb-4">
        <div class="row align-items-end">
            <div class="col-md-9">
                <label class="form-label fw-medium">Search Grants</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Search by title, year, or status...">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100" id="clearSearch">
                    <i class="bi bi-x-circle me-2"></i>Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Grants Table -->
    <div class="form-card">
        <div class="table-responsive">
            <table class="table table-hover" id="grantsTable">
                <thead>
                    <tr>
                        <th>Grant ID</th>
                        <th>Title</th>
                        <th>Financial Year</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $allgrants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($grant->grantid); ?></td>
                        <td><?php echo e($grant->title); ?></td>
                        <td><?php echo e($grant->financialyear->finyear ?? 'N/A'); ?></td>
                        <td>
                            <span class="badge <?php echo e($grant->status == 'Open' ? 'bg-success' : 'bg-secondary'); ?>">
                                <?php echo e($grant->status); ?>

                            </span>
                        </td>
                        <td><?php echo e($grant->created_at->format('M d, Y')); ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('pages.grants.viewgrant', $grant->grantid)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                <?php if(auth()->user()->haspermission('canaddoreditgrant')): ?>
                                <a href="<?php echo e(route('pages.grants.editgrant', $grant->grantid)); ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

        </div>

        <!-- Financial Years Tab -->
        <div class="tab-pane fade" id="finyears" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Financial Years Management</h4>
                <?php if(auth()->user()->haspermission('canaddoreditfinyear')): ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFinYearModal">
                    <i class="bi bi-plus-circle me-2"></i>Add Financial Year
                </button>
                <?php endif; ?>
            </div>

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
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $finyears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($year->id); ?></td>
                                <td><strong><?php echo e($year->finyear); ?></strong></td>
                                <td><?php echo e($year->startdate); ?></td>
                                <td><?php echo e($year->enddate); ?></td>
                                <td><?php echo e($year->description ?? 'N/A'); ?></td>
                                <td>
                                    <?php
                                        $now = now();
                                        $start = \Carbon\Carbon::parse($year->startdate);
                                        $end = \Carbon\Carbon::parse($year->enddate);
                                        $isCurrent = $now->between($start, $end);
                                    ?>
                                    <span class="badge <?php echo e($isCurrent ? 'bg-success' : 'bg-secondary'); ?>">
                                        <?php echo e($isCurrent ? 'Active' : 'Inactive'); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if(auth()->user()->haspermission('canupdatecurrentgrantandyear')): ?>
        <!-- Settings Tab -->
        <div class="tab-pane fade" id="settings" role="tabpanel">
            <h4 class="mb-3">Current Settings</h4>
            <div class="form-card">
                <div class="row">
                    <div class="col-md-6">
                        <form id="currentGrantForm">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label">Current Open Grant</label>
                                <select class="form-select" name="current_grantno">
                                    <option value="">Select Grant</option>
                                    <?php $__currentLoopData = $allgrants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($grant->grantid); ?>" 
                                            <?php echo e($currentsettings['current_grant'] == $grant->grantid ? 'selected' : ''); ?>>
                                        <?php echo e($grant->title); ?> (<?php echo e($grant->financialyear->finyear ?? 'N/A'); ?>)
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Current Grant</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form id="currentYearForm">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label">Current Financial Year</label>
                                <select class="form-select" name="current_fin_year">
                                    <option value="">Select Year</option>
                                    <?php $__currentLoopData = $finyears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($year->id); ?>" 
                                            <?php echo e($currentsettings['current_year'] == $year->id ? 'selected' : ''); ?>>
                                        <?php echo e($year->finyear); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Current Year</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
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
                <?php echo csrf_field(); ?>
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

<!-- Add Grant Modal -->
<div class="modal fade" id="addGrantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Grant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addGrantForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Grant Title *</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Financial Year *</label>
                        <select class="form-select" name="finyearfk" required>
                            <option value="">Select Financial Year</option>
                            <?php $__currentLoopData = $finyears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>"><?php echo e($year->finyear); ?> (<?php echo e($year->startdate); ?> - <?php echo e($year->enddate); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select class="form-select" name="status" required>
                            <option value="Open">Open</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Grant</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchInput').on('input', function() {
        const search = $(this).val().toLowerCase();
        $('#grantsTable tbody tr').each(function() {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(search));
        });
    });

    $('#clearSearch').on('click', function() {
        $('#searchInput').val('');
        $('#grantsTable tbody tr').show();
    });

    // Add grant form
    $('#addGrantForm').on('submit', function(e) {
        e.preventDefault();
        $.post('/api/v1/grants', $(this).serialize())
            .done(() => {
                ARGPortal.showSuccess('Grant added successfully');
                $('#addGrantModal').modal('hide');
                location.reload();
            })
            .fail(() => ARGPortal.showError('Failed to add grant'));
    });

    // Current settings forms
    $('#currentGrantForm').on('submit', function(e) {
        e.preventDefault();
        $.post('/api/v1/settings/current-grant', $(this).serialize())
            .done(() => ARGPortal.showSuccess('Current grant updated'))
            .fail(() => ARGPortal.showError('Failed to update current grant'));
    });

    $('#currentYearForm').on('submit', function(e) {
        e.preventDefault();
        $.post('/api/v1/settings/current-year', $(this).serialize())
            .done(() => ARGPortal.showSuccess('Current year updated'))
            .fail(() => ARGPortal.showError('Failed to update current year'));
    });

    // Financial Year form
    $('#addFinYearForm').on('submit', function(e) {
        e.preventDefault();
        $.post('/api/v1/financial-years', $(this).serialize())
            .done(() => {
                ARGPortal.showSuccess('Financial year added successfully');
                $('#addFinYearModal').modal('hide');
                location.reload();
            })
            .fail(xhr => {
                const response = xhr.responseJSON;
                ARGPortal.showError(response?.message || 'Failed to add financial year');
            });
    });

    // Date validation
    $('#startDate, #endDate').on('change', function() {
        const startDate = new Date($('#startDate').val());
        const endDate = new Date($('#endDate').val());
        
        if (startDate && endDate && startDate >= endDate) {
            ARGPortal.showError('Start date must be before end date');
            $(this).val('');
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/pages/grants/index.blade.php ENDPATH**/ ?>
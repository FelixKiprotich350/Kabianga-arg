<?php $__env->startSection('title', 'New Proposal - UoK ARG Portal'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">New Research Proposal</h2>
            <p class="text-muted mb-0">Submit your research proposal for grant consideration</p>
        </div>
        <a href="<?php echo e(route('pages.proposals.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Applications
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <form action="/api/v1/proposals" method="POST">
                <?php echo csrf_field(); ?>
                
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
                            <label for="grant" class="form-label fw-medium">Grant Type *</label>
                            <select class="form-select" id="grant" name="grantnofk" required>
                                <option value="">Select Grant</option>
                                <?php $__currentLoopData = $grants ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($grant->grantid); ?>"><?php echo e($grant->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="theme" class="form-label fw-medium">Research Theme *</label>
                            <select class="form-select" id="theme" name="themefk" required>
                                <option value="">Select Theme</option>
                                <?php $__currentLoopData = $themes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($theme->themeid); ?>"><?php echo e($theme->themename); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label fw-medium">Department *</label>
                            <select class="form-select" id="department" name="departmentfk" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pfno" class="form-label fw-medium">PF Number</label>
                            <input type="text" class="form-control" id="pfno" value="<?php echo e(Auth::user()->pfno ?? 'Not Set'); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="qualification" class="form-label fw-medium">Highest Qualification *</label>
                            <select class="form-select" id="qualification" name="highestqualification" required>
                                <option value="">Select Qualification</option>
                                <option value="PhD">PhD</option>
                                <option value="Masters">Masters</option>
                                <option value="Bachelors">Bachelors</option>
                                <option value="Diploma">Diploma</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="officephone" class="form-label fw-medium">Office Phone *</label>
                            <input type="tel" class="form-control" id="officephone" name="officephone" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cellphone" class="form-label fw-medium">Cell Phone *</label>
                            <input type="tel" class="form-control" id="cellphone" name="cellphone" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="faxnumber" class="form-label fw-medium">Fax Number *</label>
                            <input type="tel" class="form-control" id="faxnumber" name="faxnumber" required>
                        </div>
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
        

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    loadDepartments();
    
    // Form will submit normally and redirect via controller
    
    function loadDepartments() {
        $.get('/api/v1/departments/for-proposals')
            .done(function(response) {
                const data = response.data || response;
                const select = $('#department');
                select.find('option:not(:first)').remove();
                
                if (data && data.length > 0) {
                    data.forEach(dept => {
                        select.append(`<option value="${dept.depid}">${dept.shortname}</option>`);
                    });
                }
            })
            .fail(function() {
                ARGPortal.showError('Failed to load departments');
            });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/pages/proposals/create.blade.php ENDPATH**/ ?>
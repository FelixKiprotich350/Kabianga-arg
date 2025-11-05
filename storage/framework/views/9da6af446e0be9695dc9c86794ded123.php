<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h2 class="mb-1">Welcome back, <?php echo e(Auth::user()->name); ?>!</h2>
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
                    <h3 class="mt-2"><?php echo e($totalProposals); ?></h3>
                    <p class="text-muted mb-0">Total Proposals</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    <h3 class="mt-2"><?php echo e($approvedProposals); ?></h3>
                    <p class="text-muted mb-0">Approved</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                    <h3 class="mt-2"><?php echo e($pendingProposals); ?></h3>
                    <p class="text-muted mb-0">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-diagram-3 text-info" style="font-size: 2rem;"></i>
                    <h3 class="mt-2"><?php echo e($activeProjects); ?></h3>
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
                    <h4 class="mt-2"><?php echo e($completedProjects); ?></h4>
                    <p class="text-muted mb-0">Completed Projects</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-x-circle text-danger" style="font-size: 1.5rem;"></i>
                    <h4 class="mt-2"><?php echo e($cancelledProjects); ?></h4>
                    <p class="text-muted mb-0">Cancelled Projects</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-cash-stack text-success" style="font-size: 1.5rem;"></i>
                    <h4 class="mt-2"><?php echo e(number_format($totalFunding)); ?></h4>
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
                    <?php if($recentProposals->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $recentProposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?php echo e(Str::limit($proposal->researchtitle ?? 'Untitled', 50)); ?></h6>
                                        <small class="text-muted"><?php echo e($proposal->created_at->format('M d, Y')); ?></small>
                                    </div>
                                    <span class="badge 
                                        <?php if($proposal->approvalstatus == 'APPROVED'): ?> bg-success
                                        <?php elseif($proposal->approvalstatus == 'PENDING'): ?> bg-warning
                                        <?php else: ?> bg-danger
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst($proposal->approvalstatus->value)); ?>

                                    </span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No proposals yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Projects</h5>
                </div>
                <div class="card-body">
                    <?php if($recentProjects->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $recentProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?php echo e($project->researchnumber ?? 'Project'); ?></h6>
                                        <small class="text-muted"><?php echo e($project->created_at->format('M d, Y')); ?></small>
                                    </div>
                                    <span class="badge 
                                        <?php if($project->projectstatus == 'Active'): ?> bg-success
                                        <?php elseif($project->projectstatus == 'Completed'): ?> bg-info
                                        <?php else: ?> bg-secondary
                                        <?php endif; ?>">
                                        <?php echo e($project->projectstatus); ?>

                                    </span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No projects yet</p>
                    <?php endif; ?>
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
                        <?php if(Auth::user()->haspermission('canmakenewproposal')): ?>
                            <div class="col-md-3 mb-2">
                                <a href="<?php echo e(route('pages.proposals.viewnewproposal')); ?>" class="btn btn-primary w-100">
                                    <i class="bi bi-plus-circle"></i> New Proposal
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo e(route('pages.proposals.index')); ?>" class="btn btn-outline-primary w-100">
                                <i class="bi bi-files"></i> View Proposals
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo e(route('pages.projects.index')); ?>" class="btn btn-outline-info w-100">
                                <i class="bi bi-diagram-3"></i> View Projects
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo e(route('pages.myprofile')); ?>" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-person-circle"></i> My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/pages/home.blade.php ENDPATH**/ ?>
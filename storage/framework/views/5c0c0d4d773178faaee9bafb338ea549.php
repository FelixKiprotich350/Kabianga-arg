<?php $__env->startSection('title', $subject ?? 'Notification - ARG Portal'); ?>

<?php $__env->startSection('content'); ?>
    <div class="greeting"><?php echo e($greeting ?? 'Hello'); ?>,</div>
    
    <div class="message">
        <?php echo $content; ?>

    </div>
    
    <?php if(isset($actionUrl) && $actionUrl): ?>
        <a href="<?php echo e($actionUrl); ?>" class="action-button"><?php echo e($actionText ?? 'Take Action'); ?></a>
    <?php endif; ?>
    
    <?php if(isset($additionalInfo) && $additionalInfo): ?>
        <div class="info-box">
            <?php echo $additionalInfo; ?>

        </div>
    <?php endif; ?>
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280;">
        <?php echo e($footer ?? 'This is an automated message from the ARG Portal. Please do not reply to this email.'); ?>

    </p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('emails.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/emails/general-notification.blade.php ENDPATH**/ ?>
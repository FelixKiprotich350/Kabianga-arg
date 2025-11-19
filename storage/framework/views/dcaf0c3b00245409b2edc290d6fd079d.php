<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo e($subject ?? 'Notification'); ?></title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2c3e50;"><?php echo e($subject ?? 'Notification'); ?></h2>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <p><?php echo e($content ?? 'Please check your email for further instructions.'); ?></p>
            
            <?php if($actionUrl): ?>
                <div style="text-align: center; margin: 30px 0;">
                    <a href="<?php echo e($actionUrl); ?>" style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                        <?php echo e($actionText ?? 'Click Here'); ?>

                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <p style="margin-top: 30px; font-size: 14px; color: #666;">
            Best regards,<br>
            <?php echo e(config('app.name')); ?> Team
        </p>
    </div>
</body>
</html><?php /**PATH /home/felix/projects/kabianga-research-portal/arg-backend/resources/views/emails/general-notification.blade.php ENDPATH**/ ?>
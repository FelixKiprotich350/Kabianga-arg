<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Kabianga Annual Research Grants Portal">
    <meta name="author" content="University of Kabianga">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" href="<?php echo e(asset('images/logo.png')); ?>" type="image/x-icon">
    <title><?php echo $__env->yieldContent('title', 'UoK ARG Portal'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Quill.js -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo e(asset('css/modern-style.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-light">
    <?php echo $__env->make('partials.toast', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <div class="app-container">
        <?php if(auth()->guard()->check()): ?>
            <?php echo $__env->make('partials.modern-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('partials.modern-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <main class="main-content">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        <?php else: ?>
            <main class="auth-content">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        <?php endif; ?>
        
        <?php echo $__env->make('partials.modern-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Quill.js -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo e(asset('js/argportal-notifications.js')); ?>"></script>
    <script src="<?php echo e(asset('js/notifications.js')); ?>"></script>
    <script src="<?php echo e(asset('js/auth-service.js')); ?>"></script>
    <script src="<?php echo e(asset('js/api-service.js')); ?>"></script>

    <script src="<?php echo e(asset('js/data-renderers.js')); ?>"></script>
    <?php if(session('login_success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ARGPortal.user.loggedIn('<?php echo e(session('user_name', auth()->user()->name ?? 'User')); ?>');
        });
    </script>
    <?php endif; ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/layouts/app.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kabianga ARG Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 700px;
            width: 100%;
        }
        .auth-left {
            background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
            color: white;
            padding: 30px 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        .auth-right { padding: 30px 25px; }
        .logo { font-size: 2rem; font-weight: bold; margin-bottom: 15px; }
        .auth-title { font-size: 1.5rem; font-weight: 600; margin-bottom: 8px; }
        .auth-subtitle { color: #64748b; margin-bottom: 25px; }
        .form-floating { margin-bottom: 15px; }
        .form-floating > .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px 12px;
            transition: all 0.3s ease;
        }
        .form-floating > .form-control:focus {
            border-color: #2c5aa0;
            box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }
        .btn-primary:hover { transform: translateY(-1px); }
        .remember-me {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }
        .divider span {
            background: white;
            padding: 0 15px;
            color: #64748b;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .auth-left, .auth-right { padding: 25px 20px; }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="row g-0">
                <div class="col-lg-5 auth-left">
                    <div class="logo"><img src="<?php echo e(asset('images/logo.png')); ?>" alt="Kabianga University" style="height: 60px; width: auto;"></div>
                    <h2>Welcome Back!</h2>
                    <p class="mb-3">Access the Kabianga Annual Research Grants Portal.</p>
                    <div class="feature-item mb-2"><i class="fas fa-check-circle me-2"></i>Submit Proposals</div>
                    <div class="feature-item mb-2"><i class="fas fa-check-circle me-2"></i>Track Status</div>
                    <div class="feature-item"><i class="fas fa-check-circle me-2"></i>Manage Projects</div>
                </div>
                <div class="col-lg-7 auth-right">
                    <h1 class="auth-title">Sign In</h1>
                    <p class="auth-subtitle">Enter your credentials to access your account</p>

                    <?php if($errors->has('email')): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo e($errors->first('email')); ?>

                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('login.submit')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                            <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        </div>
                        <div class="remember-me">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberme" name="remember">
                                <label class="form-check-label" for="rememberme">Remember me</label>
                            </div>
                            <a href="<?php echo e(route('password.request')); ?>" class="text-decoration-none">Forgot Password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </form>

                    <div class="divider"><span>New to the platform?</span></div>
                    <div class="text-center">
                        <a href="<?php echo e(route('pages.register')); ?>" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Create New Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(asset('js/argportal-notifications.js')); ?>"></script>
    
    <script>
        <?php if(session('login_success')): ?>
            ARGPortal.user.loggedIn('<?php echo e(auth()->user()->name ?? "User"); ?>');
        <?php endif; ?>
        <?php if(session('logout_success')): ?>
            ARGPortal.user.loggedOut();
        <?php endif; ?>
        <?php if($errors->has('email')): ?>
            ARGPortal.showError('<?php echo e($errors->first('email')); ?>');
        <?php endif; ?>
    </script>
</body>
</html><?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/pages/auth/login.blade.php ENDPATH**/ ?>
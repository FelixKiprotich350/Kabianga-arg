<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kabianga ARG Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #f8f9fa;
            --accent-color: #28a745;
            --text-dark: #2d3748;
            --border-color: #e2e8f0;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }

        .auth-left {
            background: linear-gradient(135deg, var(--accent-color) 0%, #20c997 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .auth-right {
            padding: 60px 40px;
        }

        .logo {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: white;
        }

        .auth-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .auth-subtitle {
            color: #64748b;
            margin-bottom: 40px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-floating > .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 20px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-floating > .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .form-floating > label {
            color: #64748b;
            font-weight: 500;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--accent-color) 0%, #20c997 100%);
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }

        .auth-links {
            text-align: center;
            margin-top: 30px;
        }

        .auth-links a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .auth-links a:hover {
            color: #20c997;
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            background: white;
            padding: 0 20px;
            color: #64748b;
            font-size: 14px;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
        }

        .password-strength {
            margin-top: 10px;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: var(--border-color);
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #dc3545; width: 25%; }
        .strength-fair { background: #ffc107; width: 50%; }
        .strength-good { background: #fd7e14; width: 75%; }
        .strength-strong { background: #28a745; width: 100%; }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-floating {
            flex: 1;
        }

        @media (max-width: 768px) {
            .auth-left {
                padding: 40px 20px;
            }
            .auth-right {
                padding: 40px 20px;
            }
            .auth-title {
                font-size: 1.5rem;
            }
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="row g-0">
                <div class="col-lg-5 auth-left">
                    <div class="logo">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h2>Join Our Community!</h2>
                    <p class="mb-4">Create your account to access the Kabianga Annual Research Grants Portal and start your research journey.</p>
                    <div class="features">
                        <div class="feature-item mb-3">
                            <i class="fas fa-rocket me-2"></i>
                            Quick Registration Process
                        </div>
                        <div class="feature-item mb-3">
                            <i class="fas fa-shield-alt me-2"></i>
                            Secure & Protected
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-users me-2"></i>
                            Join Research Community
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 auth-right">
                    <h1 class="auth-title">Create Account</h1>
                    <p class="auth-subtitle">Fill in your information to get started</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Please correct the errors below.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.submit') }}" id="registerForm">
                        @csrf
                        <div class="form-floating">
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" required>
                            <label for="fullname"><i class="fas fa-user me-2"></i>Full Name</label>
                        </div>

                        <div class="form-row">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="pfno" name="pfno" placeholder="PF Number" required>
                                <label for="pfno"><i class="fas fa-id-badge me-2"></i>PF Number</label>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="tel" class="form-control" id="phonenumber" name="phonenumber" placeholder="Phone Number" required>
                            <label for="phonenumber"><i class="fas fa-phone me-2"></i>Phone Number</label>
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                            <div class="password-strength">
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthFill"></div>
                                </div>
                                <small class="text-muted mt-1" id="strengthText">Password strength</small>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                            <label for="confirmpassword"><i class="fas fa-lock me-2"></i>Confirm Password</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </form>

                    <div class="divider">
                        <span>Already have an account?</span>
                    </div>

                    <div class="auth-links">
                        <a href="{{ route('pages.login') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In Instead
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            let text = 'Very Weak';
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            strengthFill.className = 'strength-fill';
            
            switch(strength) {
                case 0:
                case 1:
                    strengthFill.classList.add('strength-weak');
                    text = 'Weak';
                    break;
                case 2:
                    strengthFill.classList.add('strength-fair');
                    text = 'Fair';
                    break;
                case 3:
                    strengthFill.classList.add('strength-good');
                    text = 'Good';
                    break;
                case 4:
                case 5:
                    strengthFill.classList.add('strength-strong');
                    text = 'Strong';
                    break;
            }
            
            strengthText.textContent = text;
        });

        // Password confirmation validation
        document.getElementById('confirmpassword').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Kabianga ARG Portal</title>
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
            max-width: 800px;
            width: 100%;
        }
        .auth-left {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }
        .btn-success:hover { transform: translateY(-1px); }
        .form-row { display: flex; gap: 10px; }
        .form-row .form-floating { flex: 1; }
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
            .form-row { flex-direction: column; gap: 0; }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="row g-0">
                <div class="col-lg-5 auth-left">
                    <div class="logo"><i class="fas fa-user-graduate"></i></div>
                    <h2>Join Our Community!</h2>
                    <p class="mb-3">Create your account to access the Kabianga ARG Portal.</p>
                    <div class="feature-item mb-2"><i class="fas fa-rocket me-2"></i>Quick Registration</div>
                    <div class="feature-item mb-2"><i class="fas fa-shield-alt me-2"></i>Secure & Protected</div>
                    <div class="feature-item"><i class="fas fa-users me-2"></i>Research Community</div>
                </div>
                <div class="col-lg-7 auth-right">
                    <h1 class="auth-title">Create Account</h1>
                    <p class="auth-subtitle">Fill in your information to get started</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>Please correct the errors below.
                        </div>
                    @endif

                    <form id="registerForm">
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
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                            <label for="confirmpassword"><i class="fas fa-lock me-2"></i>Confirm Password</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-decoration-none">Terms of Service</a>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </form>

                    <div class="divider"><span>Already have an account?</span></div>
                    <div class="text-center">
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
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                fullname: document.getElementById('fullname').value,
                email: document.getElementById('email').value,
                pfno: document.getElementById('pfno').value,
                phonenumber: document.getElementById('phonenumber').value,
                password: document.getElementById('password').value
            };
            
            try {
                const response = await fetch('/api/v1/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Registration successful! Please login.');
                    window.location.href = '/login';
                } else {
                    alert('Registration failed: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                alert('Registration failed: ' + error.message);
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Kabianga ARG Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .register-card { max-width: 500px; margin: 3rem auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card register-card shadow">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h3>Kabianga ARG Portal</h3>
                    <p class="text-muted">Create your account</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">Please correct the errors below.</div>
                @endif

                <form id="registerForm">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="pfno" placeholder="PF Number" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="tel" class="form-control" name="phonenumber" placeholder="Phone Number" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="confirmpassword" placeholder="Confirm Password" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the Terms of Service
                        </label>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Create Account</button>
                </form>

                <hr>
                <div class="text-center">
                    <a href="{{ route('pages.login') }}" class="btn btn-outline-success w-100">Sign In Instead</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch('/api/v1/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
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
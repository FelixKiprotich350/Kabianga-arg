<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - UoK ARG Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .auth-card { backdrop-filter: blur(10px); background: rgba(255,255,255,0.95); }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #3b82f6); border: none; }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25); }
    </style>
</head>
<body class="min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card auth-card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px;" class="mb-3">
                            <h3 class="fw-bold mb-2">Reset Password</h3>
                            <p class="text-muted small">Enter your email to receive reset link</p>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success border-0 rounded-3">
                                <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 rounded-3">
                                <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('password.requestreset') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="email" 
                                       class="form-control form-control-lg rounded-3" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="Email address" 
                                       required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 mb-3">
                                Send Reset Link
                            </button>
                        </form>

                        <div class="text-center">
                            <a href="{{ route('pages.login') }}" class="text-decoration-none small">
                                ← Back to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
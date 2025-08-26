<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kabianga ARG Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .login-card { max-width: 400px; margin: 5rem auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card login-card shadow">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h3>Kabianga ARG Portal</h3>
                    <p class="text-muted">Sign in to your account</p>
                </div>

                @if($errors->has('email'))
                    <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember">
                            <label class="form-check-label">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Sign In</button>
                </form>

                <hr>
                <div class="text-center">
                    <a href="{{ route('pages.register') }}" class="btn btn-outline-primary w-100">Create Account</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/argportal-notifications.js') }}"></script>
    
    <script>
        @if(session('login_success'))
            ARGPortal.user.loggedIn('{{ auth()->user()->name ?? "User" }}');
        @endif
        @if(session('logout_success'))
            ARGPortal.user.loggedOut();
        @endif
        @if($errors->has('email'))
            ARGPortal.showError('{{ $errors->first('email') }}');
        @endif
    </script>
</body>
</html>
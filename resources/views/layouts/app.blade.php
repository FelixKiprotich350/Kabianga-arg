<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Kabianga Annual Research Grants Portal">
    <meta name="author" content="University of Kabianga">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>@yield('title', 'UoK ARG Portal')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link href="{{ asset('css/modern-style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">
    @include('partials.toast')
    
    <div class="app-container">
        @auth
            @include('partials.modern-header')
            @include('partials.modern-sidebar')
            <main class="main-content">
                @yield('content')
            </main>
        @else
            <main class="auth-content">
                @yield('content')
            </main>
        @endauth
        
        @include('partials.modern-footer')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/argportal-notifications.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/auth-service.js') }}"></script>
    <script src="{{ asset('js/api-service.js') }}"></script>

    <script src="{{ asset('js/data-renderers.js') }}"></script>
    @if(session('login_success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ARGPortal.user.loggedIn('{{ session('user_name', auth()->user()->name ?? 'User') }}');
        });
    </script>
    @endif
    @stack('scripts')
</body>
</html>
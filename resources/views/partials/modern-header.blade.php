<header class="modern-header">
    <div class="header-content">
        <div class="d-flex align-items-center">
            <button class="sidebar-toggle me-3" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <a href="{{ route('pages.home') }}" class="logo-section">
                <img src="{{ asset('images/logo.png') }}" alt="UoK Logo">
                <div class="logo-text">
                    <h5>University of Kabianga</h5>
                    <small>Annual Research Grants Portal</small>
                </div>
            </a>
        </div>
        
        <div class="header-actions">
            <button class="notification-btn" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                    3
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">Notifications</h6></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-file-text me-2"></i>New proposal submitted</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-check-circle me-2"></i>Proposal approved</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-clock me-2"></i>Deadline reminder</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
            </ul>
            
            <div class="dropdown">
                <button class="profile-btn" data-bs-toggle="dropdown">
                    <i class="bi bi-person"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">{{ Auth::user()->name ?? 'User' }}</h6></li>
                    <li><a class="dropdown-item" href="{{ route('pages.myprofile') }}"><i class="bi bi-person me-2"></i>My Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('route.logout') }}"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
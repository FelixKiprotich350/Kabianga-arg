<header class="modern-header">
    <div class="header-content">
        <div class="d-flex align-items-center">
            <button class="sidebar-toggle me-3" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <a href="<?php echo e(route('pages.home')); ?>" class="logo-section">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="UoK Logo">
                <div class="logo-text">
                    <h5>University of Kabianga</h5>
                    <small>Annual Research Grants Portal</small>
                </div>
            </a>
        </div>
        
        <div class="header-actions">
            <div class="dropdown">
                <button class="notification-btn" data-bs-toggle="dropdown" id="notificationDropdown">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                          style="font-size: 0.6rem; display: none;" id="notificationBadge">
                        0
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="width: 350px;">
                    <li><h6 class="dropdown-header d-flex justify-content-between align-items-center">
                        Notifications
                        <button class="btn btn-sm btn-outline-primary" onclick="markAllNotificationsRead()" style="font-size: 0.7rem;">
                            Mark all read
                        </button>
                    </h6></li>
                    <div id="notificationsList" style="max-height: 300px; overflow-y: auto;">
                        <li class="text-center py-3">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </li>
                    </div>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="<?php echo e(route('notifications.index')); ?>">View all notifications</a></li>
                </ul>
            </div>
            
            <div class="dropdown">
                <button class="profile-btn" data-bs-toggle="dropdown">
                    <i class="bi bi-person"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header"><?php echo e(Auth::user()->name ?? 'User'); ?></h6></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('pages.myprofile')); ?>"><i class="bi bi-person me-2"></i>My Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?php echo e(route('route.logout')); ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</header><?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/partials/modern-header.blade.php ENDPATH**/ ?>
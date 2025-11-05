<nav class="modern-sidebar" id="sidebar">
    <div class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('pages.home') ? 'active' : ''); ?>"
                    href="<?php echo e(route('pages.home')); ?>">
                    <i class="bi bi-house-door"></i>
                    Home
                </a>
            </li>

            <?php if(Auth::user()->haspermission('canviewadmindashboard')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pages.dashboard') ? 'active' : ''); ?>"
                        href="<?php echo e(route('pages.dashboard')); ?>">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </li>
            <?php endif; ?>

            <?php if(Auth::user()->haspermission('canmakenewproposal')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pages.proposals.viewnewproposal') ? 'active' : ''); ?>"
                        href="<?php echo e(route('pages.proposals.viewnewproposal')); ?>">
                        <i class="bi bi-plus-circle"></i>
                        New Application
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('pages.proposals.index') ? 'active' : ''); ?>"
                    href="<?php echo e(route('pages.proposals.index')); ?>">
                    <i class="bi bi-files"></i>
                    Proposals
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('pages.projects.index') ? 'active' : ''); ?>"
                    href="<?php echo e(route('pages.projects.index')); ?>">
                    <i class="bi bi-diagram-3"></i>
                    Projects
                </a>
            </li>

            <?php if(Auth::user()->haspermission('cansupervise')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pages.monitoring.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('pages.monitoring.home')); ?>">
                        <i class="bi bi-clipboard-check"></i>
                        Monitoring
                    </a>
                </li>
            <?php endif; ?>

            <?php if(Auth::user()->haspermission('canviewreports')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pages.reports.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('pages.reports.home')); ?>">
                        <i class="bi bi-graph-up"></i>
                        Reports
                    </a>
                </li>
            <?php endif; ?>

            <?php if(Auth::user()->haspermission('canviewallusers')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pages.users.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('pages.users.manage')); ?>">
                        <i class="bi bi-people"></i>
                        User Management
                    </a>
                </li>
            <?php endif; ?>

            <?php if(Auth::user()->haspermission('canviewgrants')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pages.grants.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('pages.grants.home')); ?>">
                        <i class="bi bi-cash-stack"></i>
                        Grants
                    </a>
                </li>
            <?php endif; ?>

            <?php if(Auth::user()->haspermission('canviewgrants')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pages.finyears.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('pages.finyears.index')); ?>">
                        <i class="bi bi-calendar-range"></i>
                        Financial Years
                    </a>
                </li>
            <?php endif; ?>

            <?php if(Auth::user()->haspermission('canviewdepartmentsandschools')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pages.schools.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('pages.schools.home')); ?>">
                        <i class="bi bi-building"></i>
                        Schools
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('pages.themes.*') ? 'active' : ''); ?>"
                    href="<?php echo e(route('pages.themes.index')); ?>">
                    <i class="bi bi-lightbulb"></i>
                    Research Themes
                </a>
            </li>



            <li class="nav-item mt-3">
                <hr class="sidebar-divider">
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('pages.myprofile') ? 'active' : ''); ?>"
                    href="<?php echo e(route('pages.myprofile')); ?>">
                    <i class="bi bi-person-circle"></i>
                    My Account
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/partials/modern-sidebar.blade.php ENDPATH**/ ?>
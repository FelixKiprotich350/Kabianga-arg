<nav class="modern-sidebar" id="sidebar">
    <div class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pages.home') ? 'active' : '' }}"
                    href="{{ route('pages.home') }}">
                    <i class="bi bi-house-door"></i>
                    Dashboard
                </a>
            </li>

            @if (Auth::user()->haspermission('canmakenewproposal'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pages.proposals.viewnewproposal') ? 'active' : '' }}"
                        href="{{ route('pages.proposals.viewnewproposal') }}">
                        <i class="bi bi-plus-circle"></i>
                        New Application
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pages.proposals.index') ? 'active' : '' }}"
                    href="{{ route('pages.proposals.index') }}">
                    <i class="bi bi-files"></i>
                    Proposals
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pages.projects.index') ? 'active' : '' }}"
                    href="{{ route('pages.projects.index') }}">
                    <i class="bi bi-diagram-3"></i>
                    Projects
                </a>
            </li>

            @if (Auth::user()->haspermission('canviewreports'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pages.reports.*') ? 'active' : '' }}"
                        href="{{ route('pages.reports.home') }}">
                        <i class="bi bi-graph-up"></i>
                        Reports
                    </a>
                </li>
            @endif

            @if (Auth::user()->haspermission('canviewallusers'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pages.users.*') ? 'active' : '' }}"
                        href="{{ route('pages.users.manage') }}">
                        <i class="bi bi-people"></i>
                        User Management
                    </a>
                </li>
            @endif

            @if (Auth::user()->haspermission('canviewgrants'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pages.grants.*') ? 'active' : '' }}"
                        href="{{ route('pages.grants.home') }}">
                        <i class="bi bi-cash-stack"></i>
                        Grants
                    </a>
                </li>
            @endif

            @if (Auth::user()->haspermission('canviewdepartmentsandschools'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pages.schools.*') ? 'active' : '' }}"
                        href="{{ route('pages.schools.home') }}">
                        <i class="bi bi-building"></i>
                        Schools
                    </a>
                </li>
            @endif

            @if (Auth::user()->haspermission('cansupervise'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pages.monitoring.*') ? 'active' : '' }}"
                        href="{{ route('pages.monitoring.home') }}">
                        <i class="bi bi-clipboard-check"></i>
                        Monitoring
                    </a>
                </li>
            @endif

            <li class="nav-item mt-3">
                <hr class="sidebar-divider">
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pages.myprofile') ? 'active' : '' }}"
                    href="{{ route('pages.myprofile') }}">
                    <i class="bi bi-person-circle"></i>
                    My Account
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'TaxiAdmin') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Styles -->
    <link href="{{ asset('css/design-system.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-layout.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="admin-layout" data-theme="light">
    <!-- 🌟 Loading Screen -->
    <div id="loading-screen" class="loading-screen">
        <div class="loading-content">
            <div class="loading-logo">
                <div class="logo-icon bg-gradient-primary">
                    <i class="bi bi-car-front-fill"></i>
                </div>
                <h2 class="loading-title">TaxiAdmin</h2>
            </div>
            <div class="loading-spinner">
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
            </div>
            <p class="loading-text">Loading your dashboard...</p>
        </div>
    </div>

    <!-- 🎨 Main Admin Container -->
    <div class="admin-container">
        <!-- 📱 Mobile Menu Overlay -->
        <div class="mobile-overlay" id="mobileOverlay"></div>
        
        <!-- 🎛️ Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="logo-icon bg-gradient-primary">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                    <div class="logo-text">
                        <h1 class="logo-title">TaxiAdmin</h1>
                        <p class="logo-subtitle">Ultimate Control</p>
                    </div>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- User Profile Card -->
            <div class="sidebar-profile">
                <div class="profile-card glass">
                    <div class="profile-avatar">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin' }}&background=2563eb&color=fff&size=64" 
                             alt="Profile" class="avatar-img">
                        <div class="avatar-status online"></div>
                    </div>
                    <div class="profile-info">
                        <h3 class="profile-name">{{ auth()->user()->name ?? 'Super Admin' }}</h3>
                        <p class="profile-role">{{ auth()->user()->role ?? 'Administrator' }}</p>
                    </div>
                    <div class="profile-actions">
                        <button class="btn-icon btn-ghost" title="Settings">
                            <i class="bi bi-gear"></i>
                        </button>
                        <button class="btn-icon btn-ghost" title="Notifications">
                            <i class="bi bi-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <h4 class="nav-section-title">Main</h4>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <span class="nav-text">Dashboard</span>
                                <div class="nav-indicator"></div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.analytics') }}" class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-graph-up"></i>
                                <span class="nav-text">Analytics</span>
                                <span class="nav-badge badge-success">Live</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h4 class="nav-section-title">Management</h4>
                    <ul class="nav-menu">
                        <li class="nav-item has-submenu">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-people"></i>
                                <span class="nav-text">Users</span>
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </a>
                            <ul class="nav-submenu">
                                <li><a href="{{ route('admin.passengers') }}" class="nav-sublink">Passengers</a></li>
                                <li><a href="{{ route('admin.drivers') }}" class="nav-sublink">Drivers</a></li>
                                <li><a href="{{ route('admin.staff') }}" class="nav-sublink">Staff</a></li>
                            </ul>
                        </li>
                        <li class="nav-item has-submenu">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-car-front"></i>
                                <span class="nav-text">Rides</span>
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </a>
                            <ul class="nav-submenu">
                                <li><a href="{{ route('admin.rides.active') }}" class="nav-sublink">Active Rides</a></li>
                                <li><a href="{{ route('admin.rides.history') }}" class="nav-sublink">Ride History</a></li>
                                <li><a href="{{ route('admin.rides.tracking') }}" class="nav-sublink">Live Tracking</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.zones') }}" class="nav-link">
                                <i class="nav-icon bi bi-geo-alt"></i>
                                <span class="nav-text">Zones & Pricing</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h4 class="nav-section-title">Financial</h4>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.payments') }}" class="nav-link">
                                <i class="nav-icon bi bi-credit-card"></i>
                                <span class="nav-text">Payments</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.commissions') }}" class="nav-link">
                                <i class="nav-icon bi bi-percent"></i>
                                <span class="nav-text">Commissions</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports') }}" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-bar-graph"></i>
                                <span class="nav-text">Reports</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h4 class="nav-section-title">Communication</h4>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.notifications') }}" class="nav-link">
                                <i class="nav-icon bi bi-bell"></i>
                                <span class="nav-text">Notifications</span>
                                <span class="nav-badge badge-warning">12</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.messages') }}" class="nav-link">
                                <i class="nav-icon bi bi-chat-dots"></i>
                                <span class="nav-text">Messages</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.complaints') }}" class="nav-link">
                                <i class="nav-icon bi bi-exclamation-triangle"></i>
                                <span class="nav-text">Complaints</span>
                                <span class="nav-badge badge-danger">5</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h4 class="nav-section-title">System</h4>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.settings') }}" class="nav-link">
                                <i class="nav-icon bi bi-gear"></i>
                                <span class="nav-text">Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cms') }}" class="nav-link">
                                <i class="nav-icon bi bi-file-text"></i>
                                <span class="nav-text">CMS</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <div class="theme-switcher">
                    <button class="theme-toggle" id="themeToggle">
                        <i class="theme-icon bi bi-sun-fill"></i>
                        <span class="theme-text">Light Mode</span>
                    </button>
                </div>
                <div class="sidebar-version">
                    <p class="version-text">TaxiAdmin v2.0</p>
                    <p class="version-build">Build 2024.1</p>
                </div>
            </div>
        </aside>

        <!-- 📱 Main Content Area -->
        <main class="admin-main">
            <!-- 🎯 Top Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="breadcrumb-container">
                        <nav class="breadcrumb">
                            <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                                <i class="bi bi-house"></i>
                                Dashboard
                            </a>
                            @yield('breadcrumbs')
                        </nav>
                    </div>
                </div>

                <div class="header-center">
                    <div class="search-container">
                        <div class="search-input-group">
                            <i class="search-icon bi bi-search"></i>
                            <input type="text" class="search-input" placeholder="Search anything..." id="globalSearch">
                            <div class="search-shortcut">⌘K</div>
                        </div>
                    </div>
                </div>

                <div class="header-right">
                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <button class="quick-action-btn" title="Add New Ride">
                            <i class="bi bi-plus-circle"></i>
                        </button>
                        <button class="quick-action-btn" title="Live Map">
                            <i class="bi bi-geo-alt"></i>
                        </button>
                        <button class="quick-action-btn" title="Emergency">
                            <i class="bi bi-exclamation-triangle"></i>
                        </button>
                    </div>

                    <!-- Notifications -->
                    <div class="header-notifications dropdown">
                        <button class="notification-btn">
                            <i class="bi bi-bell"></i>
                            <span class="notification-count">7</span>
                        </button>
                        <div class="dropdown-menu notification-dropdown">
                            <div class="notification-header">
                                <h3>Notifications</h3>
                                <button class="mark-all-read">Mark all read</button>
                            </div>
                            <div class="notification-list">
                                <div class="notification-item unread">
                                    <div class="notification-icon bg-gradient-success">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="notification-content">
                                        <h4>New driver approved</h4>
                                        <p>John Doe has been approved as a driver</p>
                                        <span class="notification-time">2 minutes ago</span>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="notification-icon bg-gradient-warning">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                    <div class="notification-content">
                                        <h4>Payment failed</h4>
                                        <p>Payment for ride #1234 failed</p>
                                        <span class="notification-time">5 minutes ago</span>
                                    </div>
                                </div>
                            </div>
                            <div class="notification-footer">
                                <a href="{{ route('admin.notifications') }}" class="view-all-btn">View all notifications</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="user-menu dropdown">
                        <button class="user-menu-btn">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin' }}&background=2563eb&color=fff&size=40" 
                                 alt="Profile" class="user-avatar">
                            <div class="user-info">
                                <span class="user-name">{{ auth()->user()->name ?? 'Super Admin' }}</span>
                                <span class="user-role">Administrator</span>
                            </div>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu user-dropdown">
                            <div class="user-dropdown-header">
                                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin' }}&background=2563eb&color=fff&size=60" 
                                     alt="Profile" class="dropdown-avatar">
                                <div class="dropdown-user-info">
                                    <h3>{{ auth()->user()->name ?? 'Super Admin' }}</h3>
                                    <p>{{ auth()->user()->email ?? 'admin@taxiadmin.com' }}</p>
                                </div>
                            </div>
                            <div class="user-dropdown-menu">
                                <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                    <i class="bi bi-person"></i>
                                    My Profile
                                </a>
                                <a href="{{ route('admin.settings') }}" class="dropdown-item">
                                    <i class="bi bi-gear"></i>
                                    Settings
                                </a>
                                <a href="{{ route('admin.help') }}" class="dropdown-item">
                                    <i class="bi bi-question-circle"></i>
                                    Help & Support
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- 📊 Page Content -->
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="alert-icon bi bi-check-circle"></i>
                        <div class="alert-content">
                            <div class="alert-title">Success!</div>
                            <div class="alert-message">{{ session('success') }}</div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="alert-icon bi bi-exclamation-triangle"></i>
                        <div class="alert-content">
                            <div class="alert-title">Error!</div>
                            <div class="alert-message">{{ session('error') }}</div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- 🦶 Footer -->
            <footer class="admin-footer">
                <div class="footer-content">
                    <div class="footer-left">
                        <p>&copy; {{ date('Y') }} TaxiAdmin. All rights reserved.</p>
                        <p>Built with ❤️ for taxi operators worldwide</p>
                    </div>
                    <div class="footer-right">
                        <a href="#" class="footer-link">Privacy Policy</a>
                        <a href="#" class="footer-link">Terms of Service</a>
                        <a href="#" class="footer-link">Support</a>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <!-- 🔍 Global Search Modal -->
    <div class="modal-overlay" id="searchModal">
        <div class="modal search-modal">
            <div class="search-modal-header">
                <div class="search-modal-input">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search for users, rides, payments..." id="modalSearchInput">
                </div>
                <button class="modal-close" id="closeSearchModal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="search-modal-body">
                <div class="search-suggestions">
                    <h4>Quick Actions</h4>
                    <div class="suggestion-list">
                        <a href="#" class="suggestion-item">
                            <i class="bi bi-plus-circle"></i>
                            Add New Driver
                        </a>
                        <a href="#" class="suggestion-item">
                            <i class="bi bi-car-front"></i>
                            Create New Ride
                        </a>
                        <a href="#" class="suggestion-item">
                            <i class="bi bi-geo-alt"></i>
                            View Live Map
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('js/admin-layout.js') }}"></script>
    
    @stack('scripts')

    <script>
        // Hide loading screen when page is loaded
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loading-screen');
            loadingScreen.style.opacity = '0';
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 500);
        });
    </script>
</body>
</html>


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'TaxiAdmin') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Admin Layout CSS -->
    <link href="{{ asset('css/admin-layout.css') }}" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="h-full" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" 
               :class="{ 
                   'collapsed': sidebarCollapsed,
                   'mobile-open': sidebarOpen 
               }"
               x-show="sidebarOpen || window.innerWidth >= 1024"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full">
            
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="bi bi-taxi-front"></i>
                </div>
                <h1 class="sidebar-title">TaxiAdmin</h1>
            </div>
            
            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                <!-- Dashboard Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Dashboard</div>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <span class="nav-text">Overview</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.analytics') }}" 
                               class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-graph-up"></i>
                                <span class="nav-text">Analytics</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports') }}" 
                               class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-file-earmark-bar-graph"></i>
                                <span class="nav-text">Reports</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- User Management Section -->
                <div class="nav-section">
                    <div class="nav-section-title">User Management</div>
                    <ul class="nav-menu">
                        <li class="nav-item has-submenu" x-data="{ open: {{ request()->routeIs('admin.passengers.*') ? 'true' : 'false' }} }">
                            <a href="#" class="nav-link" @click="open = !open">
                                <i class="nav-icon bi bi-people"></i>
                                <span class="nav-text">Passengers</span>
                                <i class="nav-toggle bi bi-chevron-down" :class="{ 'rotate-180': open }"></i>
                            </a>
                            <ul class="nav-submenu" x-show="open" x-collapse>
                                <li class="nav-item">
                                    <a href="{{ route('admin.passengers.index') }}" 
                                       class="nav-link {{ request()->routeIs('admin.passengers.index') ? 'active' : '' }}">
                                        <span class="nav-text">All Passengers</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.passengers.blocked') }}" 
                                       class="nav-link {{ request()->routeIs('admin.passengers.blocked') ? 'active' : '' }}">
                                        <span class="nav-text">Blocked Users</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="nav-item has-submenu" x-data="{ open: {{ request()->routeIs('admin.drivers.*') ? 'true' : 'false' }} }">
                            <a href="#" class="nav-link" @click="open = !open">
                                <i class="nav-icon bi bi-person-badge"></i>
                                <span class="nav-text">Drivers</span>
                                <i class="nav-toggle bi bi-chevron-down" :class="{ 'rotate-180': open }"></i>
                            </a>
                            <ul class="nav-submenu" x-show="open" x-collapse>
                                <li class="nav-item">
                                    <a href="{{ route('admin.drivers.index') }}" 
                                       class="nav-link {{ request()->routeIs('admin.drivers.index') ? 'active' : '' }}">
                                        <span class="nav-text">All Drivers</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.drivers.pending') }}" 
                                       class="nav-link {{ request()->routeIs('admin.drivers.pending') ? 'active' : '' }}">
                                        <span class="nav-text">Pending Approval</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.drivers.documents') }}" 
                                       class="nav-link {{ request()->routeIs('admin.drivers.documents') ? 'active' : '' }}">
                                        <span class="nav-text">Document Verification</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <!-- Ride Management Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Ride Management</div>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.rides.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.rides.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-car-front"></i>
                                <span class="nav-text">All Rides</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.rides.live') }}" 
                               class="nav-link {{ request()->routeIs('admin.rides.live') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-broadcast"></i>
                                <span class="nav-text">Live Tracking</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.zones.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.zones.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-geo-alt"></i>
                                <span class="nav-text">Zones & Pricing</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Financial Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Financial</div>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.payments.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-credit-card"></i>
                                <span class="nav-text">Payments</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.commissions.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.commissions.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-percent"></i>
                                <span class="nav-text">Commissions</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.payouts.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.payouts.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-wallet2"></i>
                                <span class="nav-text">Payouts</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Communication Section -->
                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.notifications.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-bell"></i>
                                <span class="nav-text">Notifications</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.messages.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-chat-dots"></i>
                                <span class="nav-text">Messages</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.complaints.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-exclamation-triangle"></i>
                                <span class="nav-text">Complaints</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- System Section -->
                <div class="nav-section">
                    <div class="nav-section-title">System</div>
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.staff.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-person-gear"></i>
                                <span class="nav-text">Staff Management</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-gear"></i>
                                <span class="nav-text">Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.audit.index') }}" 
                               class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-shield-check"></i>
                                <span class="nav-text">Audit Logs</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <!-- Mobile Sidebar Toggle -->
                    <button type="button" 
                            class="sidebar-toggle lg:hidden" 
                            @click="sidebarOpen = !sidebarOpen">
                        <i class="bi bi-list" style="font-size: 1.25rem;"></i>
                    </button>
                    
                    <!-- Desktop Sidebar Toggle -->
                    <button type="button" 
                            class="sidebar-toggle hidden lg:block" 
                            @click="sidebarCollapsed = !sidebarCollapsed">
                        <i class="bi bi-list" style="font-size: 1.25rem;"></i>
                    </button>
                    
                    <!-- Breadcrumb -->
                    <nav class="breadcrumb">
                        @yield('breadcrumb')
                    </nav>
                </div>
                
                <div class="header-right">
                    <!-- Search -->
                    <div class="header-search">
                        <div class="relative">
                            <i class="search-icon bi bi-search"></i>
                            <input type="text" 
                                   class="search-input" 
                                   placeholder="Search..."
                                   x-data="{ query: '' }"
                                   x-model="query"
                                   @keydown.enter="performSearch(query)">
                        </div>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="header-notifications" x-data="{ open: false }">
                        <button type="button" 
                                class="notification-btn" 
                                @click="open = !open">
                            <i class="bi bi-bell" style="font-size: 1.125rem;"></i>
                            <span class="notification-badge"></span>
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <!-- Notification items would go here -->
                                <div class="p-4 text-center text-gray-500">
                                    No new notifications
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="user-menu" x-data="{ open: false }">
                        <button type="button" 
                                class="user-avatar" 
                                @click="open = !open">
                            {{ auth()->user()->initials ?? 'AD' }}
                        </button>
                        
                        <!-- User Dropdown -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="p-4 border-b border-gray-200">
                                <div class="font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin User' }}</div>
                                <div class="text-sm text-gray-500">{{ auth()->user()->email ?? 'admin@example.com' }}</div>
                            </div>
                            <div class="py-2">
                                <a href="{{ route('admin.profile') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="bi bi-person mr-2"></i>Profile
                                </a>
                                <a href="{{ route('admin.settings.account') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="bi bi-gear mr-2"></i>Account Settings
                                </a>
                                <div class="border-t border-gray-200 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="bi bi-box-arrow-right mr-2"></i>Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="admin-content">
                <!-- Page Header -->
                @hasSection('page-header')
                    <div class="page-header">
                        @yield('page-header')
                    </div>
                @endif
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        <i class="bi bi-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        <i class="bi bi-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        <i class="bi bi-exclamation-triangle mr-2"></i>
                        {{ session('warning') }}
                    </div>
                @endif
                
                <!-- Main Content -->
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"></div>
    
    <!-- Scripts -->
    <script>
        // Global search function
        function performSearch(query) {
            if (query.trim()) {
                window.location.href = `{{ route('admin.search') }}?q=${encodeURIComponent(query)}`;
            }
        }
        
        // Real-time updates (placeholder for WebSocket integration)
        function initializeRealTimeUpdates() {
            // This will be implemented with Laravel Broadcasting
            console.log('Real-time updates initialized');
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeRealTimeUpdates();
        });
    </script>
    
    @stack('scripts')
</body>
</html>

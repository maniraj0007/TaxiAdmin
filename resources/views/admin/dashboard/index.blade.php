@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <i class="bi bi-house"></i>
        <span>Dashboard</span>
    </div>
@endsection

@section('page-header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Dashboard Overview</h1>
            <p class="page-subtitle">Welcome back! Here's what's happening with your taxi business today.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" onclick="refreshDashboard()">
                <i class="bi bi-arrow-clockwise"></i>
                Refresh
            </button>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-download"></i>
                    Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard.export', ['format' => 'csv']) }}">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Export as CSV
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard.export', ['format' => 'json']) }}">
                        <i class="bi bi-file-earmark-code"></i> Export as JSON
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <!-- Total Users Card -->
        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">Total Users</h3>
                <div class="stat-icon primary">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-value" id="total-users">{{ number_format($stats['users']['total']) }}</div>
            <div class="stat-change {{ $stats['users']['growth']['direction'] }}">
                <i class="bi bi-arrow-{{ $stats['users']['growth']['direction'] === 'positive' ? 'up' : 'down' }}"></i>
                {{ $stats['users']['growth']['value'] }}% from last month
            </div>
        </div>

        <!-- Active Rides Card -->
        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">Active Rides</h3>
                <div class="stat-icon success">
                    <i class="bi bi-car-front"></i>
                </div>
            </div>
            <div class="stat-value" id="active-rides">{{ number_format($stats['rides']['active']) }}</div>
            <div class="stat-change positive">
                <i class="bi bi-arrow-up"></i>
                {{ $stats['rides']['completion_rate'] }}% completion rate
            </div>
        </div>

        <!-- Today's Revenue Card -->
        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">Today's Revenue</h3>
                <div class="stat-icon warning">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
            <div class="stat-value" id="today-revenue">${{ number_format($stats['revenue']['today'], 2) }}</div>
            <div class="stat-change {{ $stats['revenue']['growth']['direction'] }}">
                <i class="bi bi-arrow-{{ $stats['revenue']['growth']['direction'] === 'positive' ? 'up' : 'down' }}"></i>
                {{ $stats['revenue']['growth']['value'] }}% from yesterday
            </div>
        </div>

        <!-- Active Drivers Card -->
        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">Active Drivers</h3>
                <div class="stat-icon info">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
            <div class="stat-value" id="active-drivers">{{ number_format($stats['users']['active_drivers']) }}</div>
            <div class="stat-change neutral">
                <i class="bi bi-dash"></i>
                {{ round(($stats['users']['active_drivers'] / $stats['users']['drivers']) * 100, 1) }}% of total drivers
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Revenue Analytics</h3>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="revenue-period" id="revenue-7days" value="7days" checked>
                        <label class="btn btn-outline-primary btn-sm" for="revenue-7days">7 Days</label>
                        
                        <input type="radio" class="btn-check" name="revenue-period" id="revenue-30days" value="30days">
                        <label class="btn btn-outline-primary btn-sm" for="revenue-30days">30 Days</label>
                        
                        <input type="radio" class="btn-check" name="revenue-period" id="revenue-90days" value="90days">
                        <label class="btn btn-outline-primary btn-sm" for="revenue-90days">90 Days</label>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">System Performance</h3>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" height="300"></canvas>
                    
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Response Time</span>
                            <span class="fw-bold">{{ $stats['performance']['avg_response_time'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Uptime</span>
                            <span class="fw-bold text-success">{{ $stats['performance']['uptime'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Active Sessions</span>
                            <span class="fw-bold">{{ number_format($stats['performance']['active_sessions']) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Server Load</span>
                            <span class="fw-bold">{{ $stats['performance']['server_load'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rides and Users Charts -->
    <div class="row mb-4">
        <!-- Rides Chart -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daily Rides</h3>
                </div>
                <div class="card-body">
                    <canvas id="ridesChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Users Chart -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">New User Registrations</h3>
                </div>
                <div class="card-body">
                    <canvas id="usersChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Quick Actions -->
    <div class="row">
        <!-- Recent Activities -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Recent Activities</h3>
                    <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-primary btn-sm">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($recentActivities as $activity)
                            <div class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px; background-color: var(--{{ $activity['color'] }}-color); color: white;">
                                        <i class="{{ $activity['icon'] }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $activity['title'] }}</div>
                                    <div class="text-muted small">{{ $activity['description'] }}</div>
                                    <div class="text-muted small">by {{ $activity['user'] }}</div>
                                </div>
                                <div class="text-muted small">
                                    {{ $activity['time'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.drivers.pending') }}" class="btn btn-outline-primary">
                            <i class="bi bi-person-check me-2"></i>
                            Review Pending Drivers
                            <span class="badge bg-warning ms-auto">{{ rand(3, 12) }}</span>
                        </a>
                        
                        <a href="{{ route('admin.rides.live') }}" class="btn btn-outline-success">
                            <i class="bi bi-broadcast me-2"></i>
                            Live Ride Tracking
                            <span class="badge bg-success ms-auto">{{ $stats['rides']['active'] }}</span>
                        </a>
                        
                        <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Handle Complaints
                            <span class="badge bg-danger ms-auto">{{ rand(1, 5) }}</span>
                        </a>
                        
                        <a href="{{ route('admin.payouts.index') }}" class="btn btn-outline-info">
                            <i class="bi bi-wallet2 me-2"></i>
                            Process Payouts
                            <span class="badge bg-info ms-auto">${{ number_format(rand(5000, 15000)) }}</span>
                        </a>
                        
                        <a href="{{ route('admin.notifications.create') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-megaphone me-2"></i>
                            Send Notification
                        </a>
                        
                        <a href="{{ route('admin.reports.generate') }}" class="btn btn-outline-dark">
                            <i class="bi bi-file-earmark-bar-graph me-2"></i>
                            Generate Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">System Status</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Database</span>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Redis Cache</span>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Queue Workers</span>
                        <span class="badge bg-success">Running</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Payment Gateway</span>
                        <span class="badge bg-success">Connected</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>SMS Service</span>
                        <span class="badge bg-warning">Limited</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Chart.js configuration
    Chart.defaults.font.family = 'Inter, sans-serif';
    Chart.defaults.color = '#64748b';
    
    // Chart data from backend
    const chartData = @json($chartData);
    
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: chartData.revenue.labels,
            datasets: [{
                label: 'Revenue ($)',
                data: chartData.revenue.data,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Performance Chart (Doughnut)
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    const performanceChart = new Chart(performanceCtx, {
        type: 'doughnut',
        data: {
            labels: chartData.performance.labels,
            datasets: [{
                data: chartData.performance.data,
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444'
                ],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            }
        }
    });

    // Rides Chart
    const ridesCtx = document.getElementById('ridesChart').getContext('2d');
    const ridesChart = new Chart(ridesCtx, {
        type: 'bar',
        data: {
            labels: chartData.rides.labels,
            datasets: [{
                label: 'Rides',
                data: chartData.rides.data,
                backgroundColor: '#10b981',
                borderColor: '#059669',
                borderWidth: 1,
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Users Chart
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    const usersChart = new Chart(usersCtx, {
        type: 'line',
        data: {
            labels: chartData.users.labels,
            datasets: [{
                label: 'New Users',
                data: chartData.users.data,
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#f59e0b',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Real-time updates
    function refreshDashboard() {
        // Show loading state
        const refreshBtn = document.querySelector('[onclick="refreshDashboard()"]');
        const originalText = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Refreshing...';
        refreshBtn.disabled = true;

        // Fetch updated stats
        fetch('{{ route("admin.dashboard.stats") }}')
            .then(response => response.json())
            .then(data => {
                // Update stat cards
                document.getElementById('total-users').textContent = data.users.total.toLocaleString();
                document.getElementById('active-rides').textContent = data.rides.active.toLocaleString();
                document.getElementById('today-revenue').textContent = '$' + data.revenue.today.toLocaleString();
                document.getElementById('active-drivers').textContent = data.users.active_drivers.toLocaleString();

                // Reset button
                refreshBtn.innerHTML = originalText;
                refreshBtn.disabled = false;

                // Show success message
                showNotification('Dashboard refreshed successfully!', 'success');
            })
            .catch(error => {
                console.error('Error refreshing dashboard:', error);
                refreshBtn.innerHTML = originalText;
                refreshBtn.disabled = false;
                showNotification('Failed to refresh dashboard', 'error');
            });
    }

    // Period change handlers
    document.querySelectorAll('input[name="revenue-period"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                updateRevenueChart(this.value);
            }
        });
    });

    function updateRevenueChart(period) {
        fetch(`{{ route("admin.dashboard.chart-data") }}?period=${period}`)
            .then(response => response.json())
            .then(data => {
                revenueChart.data.labels = data.revenue.labels;
                revenueChart.data.datasets[0].data = data.revenue.data;
                revenueChart.update('active');
            })
            .catch(error => {
                console.error('Error updating chart:', error);
            });
    }

    // Notification function
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Auto-refresh every 30 seconds
    setInterval(() => {
        refreshDashboard();
    }, 30000);

    // Add spinning animation for refresh button
    const style = document.createElement('style');
    style.textContent = `
        .spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
        padding: 1rem;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .btn-check:checked + .btn-outline-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
</style>
@endpush

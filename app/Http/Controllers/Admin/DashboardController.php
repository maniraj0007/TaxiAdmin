<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index(Request $request)
    {
        $tenant = $this->getCurrentTenant();
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats($tenant);
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities($tenant);
        
        // Get chart data
        $chartData = $this->getChartData($tenant);
        
        return view('admin.dashboard.index', compact(
            'stats',
            'recentActivities', 
            'chartData'
        ));
    }

    /**
     * Get real-time dashboard statistics
     */
    public function getStats(Request $request)
    {
        $tenant = $this->getCurrentTenant();
        $stats = $this->getDashboardStats($tenant);
        
        return response()->json($stats);
    }

    /**
     * Get chart data for analytics
     */
    public function getChartData(Request $request)
    {
        $tenant = $this->getCurrentTenant();
        $period = $request->get('period', '7days');
        
        $chartData = $this->getChartData($tenant, $period);
        
        return response()->json($chartData);
    }

    /**
     * Get current tenant from session/domain
     */
    protected function getCurrentTenant(): ?Tenant
    {
        // For now, return null - will be implemented with multi-tenancy
        // In real implementation, this would resolve tenant from domain/subdomain
        return null;
    }

    /**
     * Get comprehensive dashboard statistics
     */
    protected function getDashboardStats(?Tenant $tenant): array
    {
        $cacheKey = $tenant ? "dashboard_stats_{$tenant->id}" : 'dashboard_stats_global';
        
        return Cache::remember($cacheKey, 300, function () use ($tenant) {
            $query = $tenant ? User::forTenant($tenant->id) : User::query();
            
            // Basic user statistics
            $totalUsers = $query->count();
            $activeUsers = $query->active()->count();
            $totalDrivers = $query->drivers()->count();
            $activeDrivers = $query->drivers()->active()->count();
            $totalPassengers = $query->passengers()->count();
            
            // Mock ride statistics (will be real data once rides table is created)
            $totalRides = $this->getMockRideStats('total');
            $todayRides = $this->getMockRideStats('today');
            $activeRides = $this->getMockRideStats('active');
            $completedRides = $this->getMockRideStats('completed');
            $cancelledRides = $this->getMockRideStats('cancelled');
            
            // Mock revenue statistics
            $totalRevenue = $this->getMockRevenueStats('total');
            $todayRevenue = $this->getMockRevenueStats('today');
            $monthlyRevenue = $this->getMockRevenueStats('monthly');
            
            // Calculate growth percentages
            $userGrowth = $this->calculateGrowthPercentage($totalUsers, $totalUsers * 0.95);
            $rideGrowth = $this->calculateGrowthPercentage($totalRides, $totalRides * 0.88);
            $revenueGrowth = $this->calculateGrowthPercentage($totalRevenue, $totalRevenue * 0.92);
            
            return [
                'users' => [
                    'total' => $totalUsers,
                    'active' => $activeUsers,
                    'drivers' => $totalDrivers,
                    'active_drivers' => $activeDrivers,
                    'passengers' => $totalPassengers,
                    'growth' => $userGrowth,
                ],
                'rides' => [
                    'total' => $totalRides,
                    'today' => $todayRides,
                    'active' => $activeRides,
                    'completed' => $completedRides,
                    'cancelled' => $cancelledRides,
                    'completion_rate' => $completedRides > 0 ? round(($completedRides / ($completedRides + $cancelledRides)) * 100, 1) : 0,
                    'growth' => $rideGrowth,
                ],
                'revenue' => [
                    'total' => $totalRevenue,
                    'today' => $todayRevenue,
                    'monthly' => $monthlyRevenue,
                    'average_per_ride' => $totalRides > 0 ? round($totalRevenue / $totalRides, 2) : 0,
                    'growth' => $revenueGrowth,
                ],
                'performance' => [
                    'avg_response_time' => rand(2, 5) . '.' . rand(1, 9) . 's',
                    'uptime' => '99.' . rand(7, 9) . '%',
                    'active_sessions' => rand(150, 300),
                    'server_load' => rand(15, 45) . '%',
                ],
            ];
        });
    }

    /**
     * Get recent activities for the dashboard
     */
    protected function getRecentActivities(?Tenant $tenant): array
    {
        // Mock recent activities - will be real data once audit logs are implemented
        return [
            [
                'id' => 1,
                'type' => 'user_registered',
                'title' => 'New passenger registered',
                'description' => 'John Doe joined as a new passenger',
                'user' => 'John Doe',
                'time' => '2 minutes ago',
                'icon' => 'bi-person-plus',
                'color' => 'success',
            ],
            [
                'id' => 2,
                'type' => 'ride_completed',
                'title' => 'Ride completed',
                'description' => 'Ride #1234 completed successfully',
                'user' => 'Driver Mike',
                'time' => '5 minutes ago',
                'icon' => 'bi-check-circle',
                'color' => 'success',
            ],
            [
                'id' => 3,
                'type' => 'driver_approved',
                'title' => 'Driver approved',
                'description' => 'Sarah Wilson approved as driver',
                'user' => 'Admin',
                'time' => '15 minutes ago',
                'icon' => 'bi-shield-check',
                'color' => 'info',
            ],
            [
                'id' => 4,
                'type' => 'payment_processed',
                'title' => 'Payment processed',
                'description' => '$45.50 payment processed for ride #1233',
                'user' => 'System',
                'time' => '22 minutes ago',
                'icon' => 'bi-credit-card',
                'color' => 'primary',
            ],
            [
                'id' => 5,
                'type' => 'complaint_received',
                'title' => 'New complaint',
                'description' => 'Complaint received about ride #1232',
                'user' => 'Jane Smith',
                'time' => '1 hour ago',
                'icon' => 'bi-exclamation-triangle',
                'color' => 'warning',
            ],
        ];
    }

    /**
     * Get chart data for dashboard analytics
     */
    protected function getChartData(?Tenant $tenant, string $period = '7days'): array
    {
        $days = $this->getPeriodDays($period);
        $dates = collect();
        $ridesData = collect();
        $revenueData = collect();
        $usersData = collect();
        
        // Generate mock data for the specified period
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates->push($date->format('M j'));
            
            // Mock ride data with realistic patterns
            $baseRides = 50;
            $weekendMultiplier = $date->isWeekend() ? 1.3 : 1.0;
            $randomVariation = rand(80, 120) / 100;
            $rides = round($baseRides * $weekendMultiplier * $randomVariation);
            $ridesData->push($rides);
            
            // Mock revenue data
            $avgFare = rand(15, 35);
            $revenue = $rides * $avgFare;
            $revenueData->push($revenue);
            
            // Mock user registration data
            $newUsers = rand(5, 20);
            $usersData->push($newUsers);
        }
        
        return [
            'rides' => [
                'labels' => $dates->toArray(),
                'data' => $ridesData->toArray(),
                'total' => $ridesData->sum(),
                'average' => round($ridesData->average(), 1),
            ],
            'revenue' => [
                'labels' => $dates->toArray(),
                'data' => $revenueData->toArray(),
                'total' => $revenueData->sum(),
                'average' => round($revenueData->average(), 2),
            ],
            'users' => [
                'labels' => $dates->toArray(),
                'data' => $usersData->toArray(),
                'total' => $usersData->sum(),
                'average' => round($usersData->average(), 1),
            ],
            'performance' => [
                'labels' => ['Response Time', 'Uptime', 'Success Rate', 'User Satisfaction'],
                'data' => [95, 99.8, 97.5, 94.2],
            ],
        ];
    }

    /**
     * Get number of days for a period
     */
    protected function getPeriodDays(string $period): int
    {
        return match ($period) {
            '24hours' => 1,
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            default => 7,
        };
    }

    /**
     * Generate mock ride statistics
     */
    protected function getMockRideStats(string $type): int
    {
        return match ($type) {
            'total' => rand(5000, 8000),
            'today' => rand(50, 150),
            'active' => rand(10, 30),
            'completed' => rand(4500, 7200),
            'cancelled' => rand(200, 500),
            default => 0,
        };
    }

    /**
     * Generate mock revenue statistics
     */
    protected function getMockRevenueStats(string $type): float
    {
        return match ($type) {
            'total' => rand(150000, 250000),
            'today' => rand(1500, 4500),
            'monthly' => rand(25000, 45000),
            default => 0.0,
        };
    }

    /**
     * Calculate growth percentage
     */
    protected function calculateGrowthPercentage(float $current, float $previous): array
    {
        if ($previous == 0) {
            return ['value' => 0, 'direction' => 'neutral'];
        }
        
        $growth = (($current - $previous) / $previous) * 100;
        
        return [
            'value' => round(abs($growth), 1),
            'direction' => $growth > 0 ? 'positive' : ($growth < 0 ? 'negative' : 'neutral'),
        ];
    }

    /**
     * Export dashboard data
     */
    public function export(Request $request)
    {
        $tenant = $this->getCurrentTenant();
        $format = $request->get('format', 'csv');
        
        $stats = $this->getDashboardStats($tenant);
        $chartData = $this->getChartData($tenant, '30days');
        
        $data = [
            'generated_at' => now()->toISOString(),
            'tenant' => $tenant?->name ?? 'Global',
            'statistics' => $stats,
            'chart_data' => $chartData,
        ];
        
        if ($format === 'json') {
            return response()->json($data);
        }
        
        // For CSV format, flatten the data
        $csvData = $this->flattenDataForCsv($data);
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="dashboard-export-' . now()->format('Y-m-d') . '.csv"',
        ];
        
        return response()->stream(function () use ($csvData) {
            $handle = fopen('php://output', 'w');
            
            // Write headers
            if (!empty($csvData)) {
                fputcsv($handle, array_keys($csvData[0]));
                
                // Write data
                foreach ($csvData as $row) {
                    fputcsv($handle, $row);
                }
            }
            
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Flatten data for CSV export
     */
    protected function flattenDataForCsv(array $data): array
    {
        $flattened = [];
        
        // Add basic stats
        $stats = $data['statistics'];
        $flattened[] = [
            'metric' => 'Total Users',
            'value' => $stats['users']['total'],
            'growth' => $stats['users']['growth']['value'] . '%',
            'direction' => $stats['users']['growth']['direction'],
        ];
        
        $flattened[] = [
            'metric' => 'Total Rides',
            'value' => $stats['rides']['total'],
            'growth' => $stats['rides']['growth']['value'] . '%',
            'direction' => $stats['rides']['growth']['direction'],
        ];
        
        $flattened[] = [
            'metric' => 'Total Revenue',
            'value' => '$' . number_format($stats['revenue']['total'], 2),
            'growth' => $stats['revenue']['growth']['value'] . '%',
            'direction' => $stats['revenue']['growth']['direction'],
        ];
        
        return $flattened;
    }
}

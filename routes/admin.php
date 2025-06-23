<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group. Make something great!
|
*/

// Admin Dashboard Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');
    
    // Analytics
    Route::get('/analytics', function () {
        return view('admin.analytics.index');
    })->name('analytics');
    
    // Reports
    Route::get('/reports', function () {
        return view('admin.reports.index');
    })->name('reports');
    Route::get('/reports/generate', function () {
        return view('admin.reports.generate');
    })->name('reports.generate');
    
    // User Management - Passengers
    Route::prefix('passengers')->name('passengers.')->group(function () {
        Route::get('/', function () {
            return view('admin.passengers.index');
        })->name('index');
        Route::get('/blocked', function () {
            return view('admin.passengers.blocked');
        })->name('blocked');
    });
    
    // User Management - Drivers
    Route::prefix('drivers')->name('drivers.')->group(function () {
        Route::get('/', function () {
            return view('admin.drivers.index');
        })->name('index');
        Route::get('/pending', function () {
            return view('admin.drivers.pending');
        })->name('pending');
        Route::get('/documents', function () {
            return view('admin.drivers.documents');
        })->name('documents');
    });
    
    // Ride Management
    Route::prefix('rides')->name('rides.')->group(function () {
        Route::get('/', function () {
            return view('admin.rides.index');
        })->name('index');
        Route::get('/live', function () {
            return view('admin.rides.live');
        })->name('live');
    });
    
    // Zone Management
    Route::prefix('zones')->name('zones.')->group(function () {
        Route::get('/', function () {
            return view('admin.zones.index');
        })->name('index');
    });
    
    // Financial Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', function () {
            return view('admin.payments.index');
        })->name('index');
    });
    
    Route::prefix('commissions')->name('commissions.')->group(function () {
        Route::get('/', function () {
            return view('admin.commissions.index');
        })->name('index');
    });
    
    Route::prefix('payouts')->name('payouts.')->group(function () {
        Route::get('/', function () {
            return view('admin.payouts.index');
        })->name('index');
    });
    
    // Communication
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', function () {
            return view('admin.notifications.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.notifications.create');
        })->name('create');
    });
    
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', function () {
            return view('admin.messages.index');
        })->name('index');
    });
    
    Route::prefix('complaints')->name('complaints.')->group(function () {
        Route::get('/', function () {
            return view('admin.complaints.index');
        })->name('index');
    });
    
    // System Management
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', function () {
            return view('admin.staff.index');
        })->name('index');
    });
    
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            return view('admin.settings.index');
        })->name('index');
        Route::get('/account', function () {
            return view('admin.settings.account');
        })->name('account');
    });
    
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/', function () {
            return view('admin.audit.index');
        })->name('index');
    });
    
    // Profile
    Route::get('/profile', function () {
        return view('admin.profile.index');
    })->name('profile');
    
    // Search
    Route::get('/search', function () {
        return view('admin.search.index');
    })->name('search');
    
});

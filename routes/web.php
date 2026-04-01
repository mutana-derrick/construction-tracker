<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectSelectionController;
use App\Http\Controllers\EquipmentLogController;
use App\Http\Controllers\EquipmentCostController;
use App\Http\Controllers\ProductivityLogController;
use App\Http\Controllers\CasualLabourLogController;
use App\Http\Controllers\MaterialUsageController;
use App\Http\Controllers\MaterialCostController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Root route - redirect to project selection if authenticated, otherwise to login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('projects.select');
    }
    return redirect()->route('login');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Project Selection
    Route::get('/projects/select', [ProjectSelectionController::class, 'index'])->name('projects.select');
    Route::post('/projects/{project}/select', [ProjectSelectionController::class, 'select'])->name('projects.select.post');
    Route::get('/projects/clear-selection', [ProjectSelectionController::class, 'clearSelection'])->name('projects.clear-selection');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('projects', ProjectController::class);
    Route::resource('equipment-logs', EquipmentLogController::class);
    Route::resource('equipment-costs', EquipmentCostController::class);
    Route::resource('productivity-logs', ProductivityLogController::class);
    Route::resource('casual-labour-logs', CasualLabourLogController::class);
    Route::resource('material-usage', MaterialUsageController::class);
    Route::resource('material-costs', MaterialCostController::class);

    // Report routes - Views
    Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');

    // Report routes - Exports
    Route::post('/reports/daily/excel', [ReportController::class, 'getDailyExcel'])->name('reports.daily.excel');
    Route::post('/reports/daily/pdf', [ReportController::class, 'getDailyPDF'])->name('reports.daily.pdf');
    Route::post('/reports/monthly/excel', [ReportController::class, 'getMonthlyExcel'])->name('reports.monthly.excel');
    Route::post('/reports/monthly/pdf', [ReportController::class, 'getMonthlyPDF'])->name('reports.monthly.pdf');
});

require __DIR__.'/auth.php';
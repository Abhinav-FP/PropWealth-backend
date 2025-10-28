<?php

use App\Http\Controllers\PdfReportController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\SuburbController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDownloadLimitController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('queue:restart');

    return "✅ All caches cleared!";
});


Route::get('/', [AuthController::class, 'showLoginForm']);

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('vendor.adminpanel.pages.dashboard');
    })->name('dashboard');

    // === REPORT ROUTES (Specific routes first, generic routes last) ===
    Route::get('/report', [PdfReportController::class, 'index'])->name('report.index');
    Route::get('/all-reports', [PdfReportController::class, 'allReports'])->name('report.all-reports');

    // Specific routes - these must come BEFORE generic {userId} route
    Route::get('/report/download/{report}', [PdfReportController::class, 'download'])->name('report.download');
    Route::delete('/report/{report}', [PdfReportController::class, 'destroy'])->name('report.destroy');
    Route::get('/reports/export-filtered', [PdfReportController::class, 'exportFiltered'])->name('reports.export-filtered');

    // Routes with specific patterns
    Route::get('/report/{userId}/export', [PdfReportController::class, 'export'])->name('report.export');

    // Generic route - MUST come last to avoid conflicts
    Route::get('/report/{userId}', [PdfReportController::class, 'userReport'])->name('report.userReport');

    // === USER MANAGEMENT ROUTES ===
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::resource('user', UserController::class)->except(['show', 'edit', 'update', 'destroy']);
    Route::get('/users/export', [UserController::class, 'export'])->name('user.export');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');

    // === DOWNLOAD LIMIT ROUTES ===
    Route::get('/download-limit', [UserDownloadLimitController::class, 'index'])->name('downloadLimit.index');
    Route::post('/download-limit', [UserDownloadLimitController::class, 'update'])->name('downloadLimit.update');

    // === SUBURB MANAGEMENT ROUTES ===
    Route::get('/suburbs', [SuburbController::class, 'adminIndex'])->name('suburbs.adminIndex');
    Route::get('/suburbs/create', [SuburbController::class, 'create'])->name('suburbs.create');
    Route::post('/suburbs', [SuburbController::class, 'store'])->name('suburbs.store');
    Route::get('/suburbs/upload', [SuburbController::class, 'showUploadForm'])->name('suburbs.upload');
    Route::post('/suburbs/import', [SuburbController::class, 'import'])->name('suburbs.import');
    Route::post('/suburbs/delete-all', [SuburbController::class, 'destroyAll'])->name('suburbs.destroyAll');
    Route::get('/suburbs/{suburb}/edit', [SuburbController::class, 'edit'])->name('suburbs.edit');
    Route::put('/suburbs/{suburb}', [SuburbController::class, 'update'])->name('suburbs.update');
    Route::delete('/suburbs/{suburb}', [SuburbController::class, 'destroy'])->name('suburbs.destroy');

    // === STATE MANAGEMENT ROUTES ===
    Route::get('/states', [StateController::class, 'index'])->name('states.index');
    Route::get('/states/create', [StateController::class, 'create'])->name('states.create');
    Route::post('/states', [StateController::class, 'store'])->name('states.store');
    Route::get('/states/upload', [StateController::class, 'showUploadForm'])->name('states.upload');
    Route::post('/states/import', [StateController::class, 'import'])->name('states.import');
    Route::post('/states/delete-all', [StateController::class, 'destroyAll'])->name('states.destroyAll');
    Route::get('/states/{state}/edit', [StateController::class, 'edit'])->name('states.edit');
    Route::put('/states/{state}', [StateController::class, 'update'])->name('states.update');
    Route::delete('/states/{state}', [StateController::class, 'destroy'])->name('states.destroy');
});

<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StationaryRequestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AdminController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - role-specific
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User profile and settings
    Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('users.update-profile');

    Route::redirect('/settings', '/settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Stationary Requests - available to teachers and HODs (and higher roles)
    Route::middleware(['role:teacher,hod,principal,trust_head,admin'])->group(function () {
        Route::resource('stationary-requests', StationaryRequestController::class);

        // Workflow operations
        Route::post('/stationary-requests/{stationaryRequest}/approve', [StationaryRequestController::class, 'approve'])
            ->middleware('check-approval-access')
            ->name('stationary-requests.approve');

        Route::post('/stationary-requests/{stationaryRequest}/reject', [StationaryRequestController::class, 'reject'])
            ->middleware('check-approval-access')
            ->name('stationary-requests.reject');

        Route::post('/stationary-requests/{stationaryRequest}/send-to-provider', [StationaryRequestController::class, 'sendToProvider'])
            ->middleware('admin-only')
            ->name('stationary-requests.send-to-provider');

        Route::post('/stationary-requests/{stationaryRequest}/mark-supplied', [StationaryRequestController::class, 'markSupplied'])
            ->middleware('provider-only')
            ->name('stationary-requests.mark-supplied');

        Route::get('/stationary-requests/{stationaryRequest}/approvals', [StationaryRequestController::class, 'viewApprovals'])
            ->name('stationary-requests.approvals');

        Route::get('/stationary-requests/{stationaryRequest}/add-items', [StationaryRequestController::class, 'addItems'])
            ->name('stationary-requests.add-items');

        Route::post('/stationary-requests/{stationaryRequest}/items', [StationaryRequestController::class, 'storeItems'])
            ->name('stationary-requests.store-items');
    });

    // Orders - admin and provider access
    Route::middleware(['role:admin,provider'])->group(function () {
        Route::resource('orders', OrderController::class);

        Route::post('/orders/{order}/confirm', [OrderController::class, 'confirm'])
            ->middleware('admin-only')
            ->name('orders.confirm');

        Route::put('/orders/{order}/delivery-status', [OrderController::class, 'updateDeliveryStatus'])
            ->middleware('provider-only')
            ->name('orders.update-delivery-status');

        Route::get('/orders/{order}/receive-items', [OrderController::class, 'receiveForm'])
            ->name('orders.receive-form');

        Route::post('/orders/{order}/receive-items', [OrderController::class, 'receiveItems'])
            ->name('orders.receive-items');

        Route::get('/orders/{order}/track', [OrderController::class, 'trackDelivery'])
            ->name('orders.track');
    });

    // Approval management - for HOD, Principal, TrustHead, Admin
    Route::middleware(['role:hod,principal,trust_head,admin'])->group(function () {
        Route::get('/approvals/pending', [ApprovalController::class, 'pending'])->name('approvals.pending');
        Route::get('/approvals/completed', [ApprovalController::class, 'completed'])->name('approvals.completed');
        Route::get('/approvals/stats', [ApprovalController::class, 'stats'])->name('approvals.stats');
        Route::get('/stationary-requests/{stationaryRequest}/workflow', [ApprovalController::class, 'workflow'])
            ->name('approvals.workflow');
    });

    // User management - admin only
    Route::middleware(['admin-only'])->group(function () {
        Route::resource('users', UserController::class);

        Route::get('/users/{user}/change-role', [UserController::class, 'changeRoleForm'])
            ->name('users.change-role-form');

        Route::put('/users/{user}/change-role', [UserController::class, 'changeRole'])
            ->name('users.change-role');

        Route::get('/users/{user}/assign-department', [UserController::class, 'assignDepartmentForm'])
            ->name('users.assign-department-form');

        Route::put('/users/{user}/assign-department', [UserController::class, 'assignDepartment'])
            ->name('users.assign-department');
    });

    // Admin panel - admin only
    Route::middleware(['admin-only'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/control-panel', [AdminController::class, 'controlPanel'])->name('control-panel');
        Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

        // Settings management
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('update-settings');

        // Vendor management
        Route::get('/vendors', [AdminController::class, 'vendors'])->name('vendors');
        Route::get('/vendors/create', [AdminController::class, 'createVendor'])->name('vendors.create');
        Route::post('/vendors', [AdminController::class, 'storeVendor'])->name('vendors.store');
        Route::get('/vendors/{vendor}/edit', [AdminController::class, 'editVendor'])->name('vendors.edit');
        Route::put('/vendors/{vendor}', [AdminController::class, 'updateVendor'])->name('vendors.update');
        Route::delete('/vendors/{vendor}', [AdminController::class, 'deleteVendor'])->name('vendors.delete');

        // Product management
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    });
});

require __DIR__.'/auth.php';

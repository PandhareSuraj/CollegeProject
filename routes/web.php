<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    // Role selection page
    Route::get('/auth/select-role', [LoginController::class, 'showRoleSelection'])->name('auth.role-selection');
    
    // Role-specific login pages
    Route::get('/auth/login/{role}', [LoginController::class, 'showLoginByRole'])->name('auth.role-login');
    Route::post('/auth/login/{role}', [LoginController::class, 'loginByRole'])->name('auth.role-login-submit');
    
    // Generic login page
    Route::get('/auth/login', [LoginController::class, 'show'])->name('auth.login');
    Route::post('/auth/login', [LoginController::class, 'login'])->name('auth.login-submit');
    
    // Alias routes - redirect /login to /auth/login
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Registration
    Route::get('/auth/register', [RegisterController::class, 'showRegistrationForm'])->name('auth.register-form');
    Route::post('/auth/register', [RegisterController::class, 'register'])->name('auth.register');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout route
Route::post('/auth/logout', [LoginController::class, 'logout'])->middleware('auth')->name('auth.logout');
Route::get('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        // Use session-stored role to ensure we maintain the correct role across requests
        $role = session('user_role') ?? ($user->role ?? 'unknown');
        
        return match($role) {
            'admin' => app(DashboardController::class)->admin(),
            'teacher' => app(DashboardController::class)->teacher(),
            'hod' => app(DashboardController::class)->hod(),
            'principal' => app(DashboardController::class)->principal(),
            'trust_head' => app(DashboardController::class)->trustHead(),
            'provider' => app(DashboardController::class)->provider(),
            default => view('dashboards.error', ['currentRole' => $role, 'user' => $user]),
        };
    })->name('dashboard');
});

// Request routes
Route::middleware(['auth', 'verified', 'check-request'])->group(function () {
    // Teachers can create requests
    Route::middleware('role:teacher,hod,admin')->group(function () {
        Route::get('/requests/create', [RequestController::class, 'create'])->name('requests.create');
        Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
        Route::get('/requests/{stationaryRequest}/edit', [RequestController::class, 'edit'])->name('requests.edit');
        Route::put('/requests/{stationaryRequest}', [RequestController::class, 'update'])->name('requests.update');
        Route::delete('/requests/{stationaryRequest}', [RequestController::class, 'destroy'])->name('requests.destroy');
    });

    // All authenticated users can view requests
    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{stationaryRequest}', [RequestController::class, 'show'])->name('requests.show');
});

// Approval routes
Route::middleware(['auth', 'verified'])->group(function () {
    // HOD, Principal, Trust Head, Admin can approve
    Route::middleware('role:hod,principal,trust_head,admin')->group(function () {
        Route::middleware('check-approval')->group(function () {
            Route::get('/approvals/{stationaryRequest}', [ApprovalController::class, 'show'])->name('approvals.show');
            Route::post('/approvals/{stationaryRequest}', [ApprovalController::class, 'store'])->name('approvals.store');
        });
    });

    // Provider can mark as supplied
    Route::middleware('check-provider')->group(function () {
        Route::post('/requests/{stationaryRequest}/supplied', [ApprovalController::class, 'markSupplied'])->name('requests.supplied');
    });
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin', 'department'])->group(function () {
    // Users management
    Route::resource('users', UserController::class)->names('admin.users');

    // Departments management
    Route::resource('departments', DepartmentController::class)->names('admin.departments');

    // Products management
    Route::resource('products', ProductController::class)->names('admin.products');

    // Vendors management
    Route::resource('vendors', \App\Http\Controllers\Admin\VendorController::class)->names('admin.vendors');

    // Orders management (admin)
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index','show','edit','update','destroy'])->names('admin.orders');
    // Update order status (admin quick action)
    Route::post('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    // Bulk create orders for requests that are sent_to_provider but have no order yet
    Route::post('orders/create-bulk', [\App\Http\Controllers\Admin\OrderController::class, 'createBulk'])->name('admin.orders.create-bulk');

    // Purchase Committees
    Route::resource('purchase-committees', \App\Http\Controllers\Admin\PurchaseCommitteeController::class)->names('admin.purchase-committees');

    // College Section
    Route::get('college-section', [\App\Http\Controllers\Admin\CollegeSectionController::class, 'index'])->name('admin.college-section');
    Route::get('college-section/sanstha', [\App\Http\Controllers\Admin\CollegeSectionController::class, 'sanstha'])->name('admin.college-section.sanstha');
    Route::get('college-section/college', [\App\Http\Controllers\Admin\CollegeSectionController::class, 'college'])->name('admin.college-section.college');
    Route::get('college-section/department', [\App\Http\Controllers\Admin\CollegeSectionController::class, 'department'])->name('admin.college-section.department');
    Route::get('college-section/department-users', [\App\Http\Controllers\Admin\CollegeSectionController::class, 'departmentUsers'])->name('admin.college-section.department-users');

    // Order Reports
    Route::get('order-reports', [\App\Http\Controllers\Admin\OrderReportController::class, 'index'])->name('admin.order-reports.index');
    Route::post('order-reports', [\App\Http\Controllers\Admin\OrderReportController::class, 'index'])->name('admin.order-reports.submit');

    // Sanstha / College / Department quick-create routes used by College Section
    Route::get('sansthas/create', [\App\Http\Controllers\Admin\SansthaController::class, 'create'])->name('admin.sansthas.create');
    Route::post('sansthas', [\App\Http\Controllers\Admin\SansthaController::class, 'store'])->name('admin.sansthas.store');

    Route::get('colleges/create', [\App\Http\Controllers\Admin\CollegeController::class, 'create'])->name('admin.colleges.create');
    Route::post('colleges', [\App\Http\Controllers\Admin\CollegeController::class, 'store'])->name('admin.colleges.store');

    Route::get('departments/create', [\App\Http\Controllers\Admin\DepartmentController::class, 'create'])->name('admin.departments.create');
    Route::post('departments', [\App\Http\Controllers\Admin\DepartmentController::class, 'store'])->name('admin.departments.store');

    // Admin dashboard
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
});

require __DIR__.'/settings.php';

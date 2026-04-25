<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Higher-order module grouping for the PlayStation Shop SaaS Blade system.
| Shared business logic with the API layer through Domain Controllers.
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    $plans = \App\Models\Plan::all();
    return view('welcome', compact('plans'));
});

// Auth handled by Breeze in routes/auth.php

// Language Switcher (Publicly Accessible)
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
    }
    return back();
})->name('lang.switch');

// Super Admin System Auth
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::get('login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'create'])->name('login');
    Route::post('login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'store'])->name('login.store');
    Route::post('logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'destroy'])->name('logout');
});

// Super Admin System Management
Route::group(['middleware' => ['auth', 'super-admin'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::get('/', [\App\Http\Controllers\Admin\SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/tenants', [\App\Http\Controllers\Admin\SuperAdminController::class, 'tenants'])->name('tenants');
    Route::get('/tenants/create', [\App\Http\Controllers\Admin\SuperAdminController::class, 'createTenant'])->name('tenants.create');
    Route::post('/tenants', [\App\Http\Controllers\Admin\SuperAdminController::class, 'storeTenant'])->name('tenants.store');
    Route::get('/tenants/{tenant}/edit', [\App\Http\Controllers\Admin\SuperAdminController::class, 'editTenant'])->name('tenants.edit');
    Route::put('/tenants/{tenant}', [\App\Http\Controllers\Admin\SuperAdminController::class, 'updateTenant'])->name('tenants.update');
    Route::post('/tenants/{tenant}/toggle', [\App\Http\Controllers\Admin\SuperAdminController::class, 'toggleTenantStatus'])->name('tenants.toggle');
    Route::delete('/tenants/{tenant}', [\App\Http\Controllers\Admin\SuperAdminController::class, 'deleteTenant'])->name('tenants.delete');
    Route::get('/plans', [\App\Http\Controllers\Admin\SuperAdminController::class, 'plans'])->name('plans');
    Route::get('/plans/create', [\App\Http\Controllers\Admin\SuperAdminController::class, 'createPlan'])->name('plans.create');
    Route::post('/plans', [\App\Http\Controllers\Admin\SuperAdminController::class, 'storePlan'])->name('plans.store');
    Route::get('/plans/{plan}/edit', [\App\Http\Controllers\Admin\SuperAdminController::class, 'editPlan'])->name('plans.edit');
    Route::put('/plans/{plan}', [\App\Http\Controllers\Admin\SuperAdminController::class, 'updatePlan'])->name('plans.update');
    Route::delete('/plans/{plan}', [\App\Http\Controllers\Admin\SuperAdminController::class, 'deletePlan'])->name('plans.delete');
    Route::get('/users', [\App\Http\Controllers\Admin\SuperAdminController::class, 'users'])->name('users');
    Route::get('/users/create', [\App\Http\Controllers\Admin\SuperAdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\SuperAdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\SuperAdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\SuperAdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/roles', [\App\Http\Controllers\Admin\SuperAdminController::class, 'roles'])->name('roles');
    Route::get('/roles/create', [\App\Http\Controllers\Admin\SuperAdminController::class, 'createRole'])->name('roles.create');
    Route::post('/roles', [\App\Http\Controllers\Admin\SuperAdminController::class, 'storeRole'])->name('roles.store');
    Route::get('/roles/{role}/edit', [\App\Http\Controllers\Admin\SuperAdminController::class, 'editRole'])->name('roles.edit');
    Route::put('/roles/{role}', [\App\Http\Controllers\Admin\SuperAdminController::class, 'updateRole'])->name('roles.update');
    Route::delete('/roles/{role}', [\App\Http\Controllers\Admin\SuperAdminController::class, 'deleteRole'])->name('roles.delete');
    Route::get('/reports', [\App\Http\Controllers\Admin\SuperAdminController::class, 'reports'])->name('reports');
});

// Primary Administrative Shell
Route::middleware(['auth', 'verified', 'identify-tenant'])->group(function () {
    
    // Core Dashboard & Analytics
    Route::get('/dashboard', function() {
        $user = auth()->user();
        $reportingService = app(\App\Domains\Reports\Services\ReportingService::class);
        
        $recentSessions = \App\Models\Session::with(['device', 'user'])
            ->latest()
            ->limit(6)
            ->get();
        
        $activeDevicesCount = \App\Models\Device::where('status', 'IN_USE')->count();
        $todayRevenue = \App\Models\Order::whereDate('created_at', today())->sum('total_price') + 
                        \App\Models\Session::whereDate('created_at', today())->where('status', 'completed')->sum('cost');
        
        $totalSessionsToday = \App\Models\Session::whereDate('created_at', today())->count();
        $todayExpenses = \App\Models\Expense::whereDate('date', today())->sum('amount');

        // Shift Data
        $currentShift = $user->shifts()->active()->first();
        $shiftRevenue = 0;
        if ($currentShift) {
            $shiftRevenue = $currentShift->sessions()->sum('cost') + $currentShift->orders()->sum('total_price');
        }

        $topDevices = $reportingService->getTopDevices(4);

        return view('dashboard', compact(
            'recentSessions', 
            'activeDevicesCount', 
            'todayRevenue', 
            'totalSessionsToday',
            'todayExpenses',
            'currentShift',
            'shiftRevenue',
            'topDevices'
        ));
    })->name('dashboard');

    // Devices & Inventory Module
    Route::group(['prefix' => 'devices', 'as' => 'devices.'], function() {
        Route::middleware('permission:devices.manage')->group(function() {
            Route::get('create', [\App\Domains\Inventory\Controllers\Web\DeviceController::class, 'create'])->name('create');
            Route::post('/', [\App\Domains\Inventory\Controllers\Web\DeviceController::class, 'store'])->name('store');
            Route::get('{device}/edit', [\App\Domains\Inventory\Controllers\Web\DeviceController::class, 'edit'])->name('edit');
            Route::patch('{device}', [\App\Domains\Inventory\Controllers\Web\DeviceController::class, 'update'])->name('update');
            Route::delete('{device}', [\App\Domains\Inventory\Controllers\Web\DeviceController::class, 'destroy'])->name('destroy');
        });
        
        Route::middleware('permission:devices.view')->group(function() {
            Route::get('/', [\App\Domains\Inventory\Controllers\Web\DeviceController::class, 'index'])->name('index');
            Route::get('{device}', [\App\Domains\Inventory\Controllers\Web\DeviceController::class, 'show'])->name('show');
        });
        
        // Dynamic Session Actions
        Route::middleware(['permission:sessions.manage', 'shift-active'])->group(function() {
            Route::post('{device}/start', [\App\Domains\Sessions\Controllers\Web\WebSessionController::class, 'start'])->name('sessions.start');
            Route::post('{device}/stop', [\App\Domains\Sessions\Controllers\Web\WebSessionController::class, 'stop'])->name('sessions.stop');
        });
    });

    // Historic Auditing Module
    Route::group(['prefix' => 'sessions', 'as' => 'sessions.'], function() {
        Route::get('/', [\App\Domains\Sessions\Controllers\Web\WebSessionController::class, 'index'])->name('index');
        Route::get('{session}/receipt', [\App\Domains\Sessions\Controllers\Web\WebSessionController::class, 'receipt'])->name('receipt');
    });

    // POS & Retail Module
    Route::group(['as' => 'pos.'], function() {
        Route::get('/pos', [\App\Domains\POS\Controllers\POSController::class, 'index'])->name('index');
        Route::middleware(['permission:pos.orders', 'shift-active'])->post('/orders', [\App\Domains\POS\Controllers\POSController::class, 'store'])->name('orders.store');
    });

    // Order History Module
    Route::get('/orders/export', [\App\Domains\POS\Controllers\Web\OrderController::class, 'export'])->name('orders.export');
    Route::get('/orders', [\App\Domains\POS\Controllers\Web\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Domains\POS\Controllers\Web\OrderController::class, 'show'])->name('orders.show');

    // Retail Inventory Management
    Route::middleware('permission:pos.orders')->group(function() {
        Route::resource('products', \App\Domains\POS\Controllers\Web\ProductController::class);
        Route::resource('categories', \App\Domains\POS\Controllers\Web\CategoryController::class);
    });

    // Finance & Expenditure Module (Owner Only)
    Route::middleware('role:owner')->group(function () {
        Route::group(['prefix' => 'expenses', 'as' => 'expenses.'], function() {
            Route::get('/', [\App\Domains\Finance\Controllers\Web\ExpenseController::class, 'index'])->name('index');
            Route::post('/', [\App\Domains\Finance\Controllers\Web\ExpenseController::class, 'store'])->name('store');
            Route::put('/{expense}', [\App\Domains\Finance\Controllers\Web\ExpenseController::class, 'update'])->name('update');
            Route::delete('/{expense}', [\App\Domains\Finance\Controllers\Web\ExpenseController::class, 'destroy'])->name('destroy');
            
            Route::resource('categories', \App\Domains\Finance\Controllers\Web\ExpenseCategoryController::class);
        });

        // Strategic Reports Module
        Route::group(['prefix' => 'reports', 'as' => 'reports.'], function() {
            Route::get('/', [\App\Domains\Reports\Controllers\Web\ReportController::class, 'index'])->name('index');
        });
    });

    // Global Search Analytics
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'query'])->name('search');

    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Platform Configuration
    Route::group(['prefix' => 'shifts', 'as' => 'shifts.'], function() {
        Route::get('/', [\App\Http\Controllers\Web\ShiftController::class, 'index'])->name('index');
        Route::get('/start', [\App\Http\Controllers\Web\ShiftController::class, 'startPage'])->name('start');
        Route::get('/active', [\App\Http\Controllers\Web\ShiftController::class, 'activePage'])->name('active');
        Route::post('/start', [\App\Http\Controllers\Web\ShiftController::class, 'start'])->name('store');
        Route::post('/close', [\App\Http\Controllers\Web\ShiftController::class, 'close'])->name('close');
        Route::get('/{shift}/summary', [\App\Http\Controllers\Web\ShiftController::class, 'show'])->name('summary');
        Route::get('/{shift}/print', [\App\Http\Controllers\Web\ShiftController::class, 'print'])->name('print');
    });

    Route::get('settings', [\App\Http\Controllers\Web\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/profile', [\App\Http\Controllers\Web\SettingsController::class, 'updateProfile'])->name('settings.profile.update');

    // User & Role Management (Owner Only)
    Route::middleware('role:owner')->group(function() {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::get('roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
        Route::post('roles', [\App\Http\Controllers\RoleController::class, 'store'])->name('roles.store');
        Route::put('roles/{role}', [\App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');
    });

});

require __DIR__.'/auth.php';

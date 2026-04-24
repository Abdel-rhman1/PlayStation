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
    return redirect()->route('dashboard');
});

// Auth handled by Breeze in routes/auth.php

// Primary Administrative Shell
Route::middleware(['auth', 'verified', 'identify-tenant'])->group(function () {
    
    // Core Dashboard & Analytics
    Route::get('/dashboard', function() {
        $user = auth()->user();
        $recentSessions = \App\Models\Session::with(['device', 'user'])
            ->latest()
            ->limit(6)
            ->get();
        
        $activeDevicesCount = \App\Models\Device::where('status', 'IN_USE')->count();
        $todayRevenue = \App\Models\Order::whereDate('created_at', today())->sum('total_price');
        $totalSessionsToday = \App\Models\Session::whereDate('created_at', today())->count();

        // Shift Data
        $currentShift = $user->shifts()->active()->first();
        $shiftRevenue = 0;
        if ($currentShift) {
            $shiftRevenue = $currentShift->sessions()->sum('total_price') + $currentShift->orders()->sum('total_price');
        }

        return view('dashboard', compact(
            'recentSessions', 
            'activeDevicesCount', 
            'todayRevenue', 
            'totalSessionsToday',
            'currentShift',
            'shiftRevenue'
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
        
        Route::get('/', [\App\Domains\Inventory\Controllers\Web\DeviceController::class, 'index'])->name('index');
        Route::get('{device}', [\App\Domains\Inventory\Controllers\Web\DeviceController::class, 'show'])->name('show');
        
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
    Route::middleware('permission:pos.orders')->resource('products', \App\Domains\POS\Controllers\Web\ProductController::class);

    // Finance & Expenditure Module (Owner Only)
    Route::middleware('role:owner')->group(function () {
        Route::group(['prefix' => 'expenses', 'as' => 'expenses.'], function() {
            Route::get('/', [\App\Domains\Finance\Controllers\Web\ExpenseController::class, 'index'])->name('index');
            Route::middleware('shift-active')->post('/', [\App\Domains\Finance\Controllers\Web\ExpenseController::class, 'store'])->name('store');
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

    Route::get('settings', function() {
        return view('settings.index');
    })->name('settings.index');

    // Language Switcher
    Route::get('lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'ar'])) {
            session()->put('locale', $locale);
        }
        return back();
    })->name('lang.switch');

    // User & Role Management (Owner Only)
    Route::middleware('role:owner')->group(function() {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::get('roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
        Route::post('roles', [\App\Http\Controllers\RoleController::class, 'store'])->name('roles.store');
        Route::put('roles/{role}', [\App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');
    });

});

require __DIR__.'/auth.php';

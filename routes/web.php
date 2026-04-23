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

use App\Domains\Auth\Controllers\Web\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Primary Administrative Shell
Route::middleware(['auth:sanctum', 'verified', 'identify-tenant'])->group(function () {
    
    // Core Dashboard & Analytics
    Route::get('/dashboard', function() {
        $recentSessions = \App\Models\Session::with(['device', 'user'])
            ->latest()
            ->limit(6)
            ->get();
        
        $activeDevicesCount = \App\Models\Device::where('status', 'IN_USE')->count();
        
        $todayRevenue = \App\Models\Order::whereDate('created_at', today())->sum('total_price');
        $totalSessionsToday = \App\Models\Session::whereDate('created_at', today())->count();

        return view('dashboard', compact('recentSessions', 'activeDevicesCount', 'todayRevenue', 'totalSessionsToday'));
    })->name('dashboard');

    // Devices & Inventory Module
    Route::group(['prefix' => 'devices', 'as' => 'devices.'], function() {
        Route::resource('/', \App\Domains\Inventory\Controllers\Web\DeviceController::class)
            ->parameter('', 'device')
            ->names(['index' => 'index', 'store' => 'store', 'show' => 'show', 'update' => 'update', 'destroy' => 'destroy']);
        
        // Dynamic Session Actions
        Route::post('{device}/start', [\App\Domains\Sessions\Controllers\Web\WebSessionController::class, 'start'])->name('sessions.start');
        Route::post('{device}/stop', [\App\Domains\Sessions\Controllers\Web\WebSessionController::class, 'stop'])->name('sessions.stop');
    });

    // Historic Auditing Module
    Route::group(['prefix' => 'sessions', 'as' => 'sessions.'], function() {
        Route::get('/', [\App\Domains\Sessions\Controllers\Web\WebSessionController::class, 'index'])->name('index');
    });

    // POS & Retail Module
    Route::group(['as' => 'pos.'], function() {
        Route::get('/pos', [\App\Domains\POS\Controllers\POSController::class, 'index'])->name('index');
        Route::post('/orders', [\App\Domains\POS\Controllers\POSController::class, 'store'])->name('orders.store');
    });

    // Order History Module
    Route::get('/orders/export', [\App\Domains\POS\Controllers\Web\OrderController::class, 'export'])->name('orders.export');
    Route::get('/orders', [\App\Domains\POS\Controllers\Web\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Domains\POS\Controllers\Web\OrderController::class, 'show'])->name('orders.show');

    // Retail Inventory Management
    Route::resource('products', \App\Domains\POS\Controllers\Web\ProductController::class);

    // Finance & Expenditure Module
    Route::group(['prefix' => 'expenses', 'as' => 'expenses.'], function() {
        Route::get('/', [\App\Domains\Finance\Controllers\Web\ExpenseController::class, 'index'])->name('index');
        Route::post('/', [\App\Domains\Finance\Controllers\Web\ExpenseController::class, 'store'])->name('store');
    });

    // Strategic Reports Module
    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function() {
        Route::get('/', [\App\Domains\Reports\Controllers\Web\ReportController::class, 'index'])->name('index');
    });

    // Global Search Analytics
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'query'])->name('search');

    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Platform Configuration
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

});

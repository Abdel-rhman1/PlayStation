<?php

namespace App\Domains\Reports\Services;

use App\Models\Expense;
use App\Models\Order;
use App\Models\Product;
use App\Models\Session;
use Illuminate\Support\Facades\DB;

class ReportingService
{
    /**
     * Get financial summary (Revenue, Expenses, Profit).
     */
    public function getFinancialSummary(array $filters): array
    {
        $sessionRevenue = Session::where('status', 'completed')
            ->when(isset($filters['from']), fn($q) => $q->whereDate('started_at', '>=', $filters['from']))
            ->when(isset($filters['to']), fn($q) => $q->whereDate('started_at', '<=', $filters['to']))
            ->sum('cost');

        $posRevenue = Order::where('payment_status', 'paid')
            ->when(isset($filters['from']), fn($q) => $q->whereDate('created_at', '>=', $filters['from']))
            ->when(isset($filters['to']), fn($q) => $q->whereDate('created_at', '<=', $filters['to']))
            ->sum('total_price');

        $totalExpenses = Expense::when(isset($filters['from']), fn($q) => $q->whereDate('date', '>=', $filters['from']))
            ->when(isset($filters['to']), fn($q) => $q->whereDate('date', '<=', $filters['to']))
            ->sum('amount');

        $totalRevenue = $sessionRevenue + $posRevenue;
        $netProfit = $totalRevenue - $totalExpenses;

        return [
            'total_revenue' => round($totalRevenue, 2),
            'session_revenue' => round($sessionRevenue, 2),
            'pos_revenue' => round($posRevenue, 2),
            'total_expenses' => round($totalExpenses, 2),
            'net_profit' => round($netProfit, 2),
        ];
    }

    /**
     * Get top performing devices by revenue.
     */
    public function getTopDevices(int $limit = 5): \Illuminate\Support\Collection
    {
        // Debug Log
        \Illuminate\Support\Facades\Log::info('Calculating top devices for dashboard.');

        return \App\Models\Device::select('devices.name', 'devices.id')
            ->withSum(['sessions' => function($q) {
                $q->where('status', 'completed')
                  ->whereDate('started_at', today());
            }], 'cost')
            ->withCount(['sessions' => function($q) {
                $q->whereDate('started_at', today());
            }])
            ->get()
            ->map(function($device) {
                // Calculate usage percentage today (clipped to today's boundaries)
                $today = today();
                $tomorrow = today()->addDay();

                $totalMinutes = \App\Models\Session::where('device_id', $device->id)
                    ->where(function($q) use ($today) {
                        $q->whereDate('started_at', $today)
                          ->orWhere(function($sq) use ($today) {
                              $sq->where('started_at', '<', $today)
                                 ->where(function($inner) {
                                     $inner->whereNull('ended_at')
                                           ->orWhere('ended_at', '>=', today());
                                 });
                          });
                    })
                    ->get()
                    ->sum(function($session) use ($today, $tomorrow) {
                        $start = $session->started_at->max($today);
                        $end = ($session->ended_at ?: now())->min($tomorrow);
                        
                        return max(0, $start->diffInMinutes($end));
                    });

                $device->total_revenue = (float) $device->sessions_sum_cost ?: 0;
                $device->usage_percentage = round(($totalMinutes / 1440) * 100, 1);
                
                \Illuminate\Support\Facades\Log::debug("Usage Calculation for Device: {$device->name}", [
                    'device_id' => $device->id,
                    'total_minutes_today' => $totalMinutes,
                    'utilization_pct' => $device->usage_percentage
                ]);

                return $device;
            })
            ->sortByDesc('total_revenue')
            ->take($limit);
    }

    /**
     * Get top selling products by quantity.
     */
    public function getTopProducts(int $limit = 5): \Illuminate\Support\Collection
    {
        return Product::join('order_items', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }
}

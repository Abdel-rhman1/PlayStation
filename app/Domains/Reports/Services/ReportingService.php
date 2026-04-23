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

        $posRevenue = Order::where('status', 'paid')
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
        return DB::table('sessions')
            ->join('devices', 'sessions.device_id', '=', 'devices.id')
            ->select('devices.name', DB::raw('SUM(sessions.cost) as total_revenue'))
            ->where('sessions.status', 'completed')
            ->groupBy('devices.id', 'devices.name')
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->get();
    }

    /**
     * Get top selling products by quantity.
     */
    public function getTopProducts(int $limit = 5): \Illuminate\Support\Collection
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }
}

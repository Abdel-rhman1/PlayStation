<?php

namespace App\Domains\Reports\Controllers\Web;

use App\Domains\Reports\Services\ReportingService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportingService $reportingService
    ) {}

    /**
     * Display report dashboard.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'from' => 'nullable|date',
            'to'   => 'nullable|date',
        ]);

        $from = $filters['from'] ?? now()->startOfMonth()->format('Y-m-d');
        $to   = $filters['to']   ?? now()->format('Y-m-d');

        $summary     = $this->reportingService->getFinancialSummary(['from' => $from, 'to' => $to]);
        $topDevices  = $this->reportingService->getTopDevices();
        $topProducts = $this->reportingService->getTopProducts();

        // Flatten summary keys for direct use in view
        $totalRevenue  = $summary['total_revenue'];
        $totalExpenses = $summary['total_expenses'];
        $netProfit     = $summary['net_profit'];

        // Revenue chart: daily revenue over the selected range
        $revenueDays = \App\Models\Session::where('status', 'completed')
            ->whereDate('started_at', '>=', $from)
            ->whereDate('started_at', '<=', $to)
            ->selectRaw('DATE(started_at) as day, SUM(cost) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $posRevenueDays = \App\Models\Order::where('payment_status', 'paid')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->selectRaw('DATE(created_at) as day, SUM(total_price) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        // Merge session + POS revenue per day
        $allDays = $revenueDays->keys()->merge($posRevenueDays->keys())->unique()->sort()->values();
        $revenueChartData = [
            'labels' => $allDays->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))->toArray(),
            'values' => $allDays->map(fn($d) => round(($revenueDays[$d] ?? 0) + ($posRevenueDays[$d] ?? 0), 2))->toArray(),
        ];

        // Expense chart: by type
        $expensesByCategory = \App\Models\Expense::whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->selectRaw('type as category, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'category');

        $expenseChartData = [
            'labels' => $expensesByCategory->keys()->toArray(),
            'values' => $expensesByCategory->values()->map(fn($v) => round($v, 2))->toArray(),
        ];

        return view('reports.index', compact(
            'summary', 'topDevices', 'topProducts',
            'totalRevenue', 'totalExpenses', 'netProfit',
            'revenueChartData', 'expenseChartData'
        ));
    }
}

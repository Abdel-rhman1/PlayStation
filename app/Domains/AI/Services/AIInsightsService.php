<?php

namespace App\Domains\AI\Services;

use App\Models\Device;
use App\Models\Session;
use Illuminate\Support\Facades\DB;

class AIInsightsService
{
    /**
     * Generate comprehensive business insights.
     */
    public function generateInsights(): array
    {
        return [
            'anomalies' => $this->detectAnomalies(),
            'predictions' => $this->predictBusyHours(),
            'optimizations' => $this->suggestOptimizations(),
        ];
    }

    /**
     * Look for suspicious patterns (e.g., sessions stopped immediately after start).
     */
    protected function detectAnomalies(): array
    {
        $suspiciousSessions = Session::where('status', 'completed')
            ->whereRaw('TIMESTAMPDIFF(SECOND, started_at, ended_at) < 60')
            ->limit(5)
            ->get();

        if ($suspiciousSessions->isEmpty()) {
            return [
                'status' => 'secure',
                'message' => 'No suspicious activity detected in the last 24 hours.',
            ];
        }

        return [
            'status' => 'warning',
            'message' => 'Detected multiple ultra-short sessions. This may indicate manual device overrides or testing.',
            'count' => $suspiciousSessions->count(),
            'details' => $suspiciousSessions->pluck('id'),
        ];
    }

    /**
     * Analyze historical data to predict peaks.
     */
    protected function predictBusyHours(): array
    {
        $peaks = Session::select(DB::raw('HOUR(started_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderByDesc('count')
            ->limit(3)
            ->get();

        return [
            'message' => 'Based on historical trends, expectancy for peak traffic is high during these hours.',
            'peak_slots' => $peaks->map(fn($item) => "{$item->hour}:00"),
        ];
    }

    /**
     * Suggest pricing or operational improvements.
     */
    protected function suggestOptimizations(): array
    {
        $avgUtilization = Device::count() > 0 ? (Session::where('status', 'active')->count() / Device::count()) : 0;

        $suggestions = [];

        if ($avgUtilization > 0.8) {
            $suggestions[] = 'High demand detected. Consider implementing a peak-hour premium rate (+15%).';
        }

        if (Session::whereDate('started_at', now()->subDays(7))->count() < 5) {
            $suggestions[] = 'Low weekday traction. Try offering a "Happy Hour" discount between 12:00 PM and 4:00 PM.';
        }

        return [
            'utilization_rate' => round($avgUtilization * 100, 2) . '%',
            'recommendations' => $suggestions ?: ['Maintain current pricing strategy. Data shows stable performance.'],
        ];
    }
}

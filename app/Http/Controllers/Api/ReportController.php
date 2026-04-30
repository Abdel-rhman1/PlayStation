<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    /**
     * Daily Revenue Report
     * GET /api/reports/daily-revenue
     */
    public function dailyRevenue(Request $request): JsonResponse
    {
        $query = Session::query()
            ->join('devices', 'sessions.device_id', '=', 'devices.id')
            ->where('sessions.status', 'completed')
            ->whereNotNull('sessions.ended_at');

        $this->applyFilters($query, $request);

        $results = $query
            ->selectRaw('DATE(sessions.ended_at) as date, SUM(sessions.cost) as total_revenue')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * Sessions Per Device Report
     * GET /api/reports/sessions-per-device
     */
    public function sessionsPerDevice(Request $request): JsonResponse
    {
        $query = Session::query()
            ->join('devices', 'sessions.device_id', '=', 'devices.id')
            ->selectRaw('devices.name as device_name, COUNT(sessions.id) as total_sessions');

        $this->applyFilters($query, $request);

        $results = $query
            ->groupBy('devices.id', 'devices.name')
            ->orderBy('total_sessions', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * Total Playtime Report (in minutes)
     * GET /api/reports/total-playtime
     */
    public function totalPlaytime(Request $request): JsonResponse
    {
        $query = Session::query()
            ->join('devices', 'sessions.device_id', '=', 'devices.id')
            ->where('sessions.status', 'completed')
            ->whereNotNull('sessions.ended_at');

        $this->applyFilters($query, $request);

        // Uses MySQL TIMESTAMPDIFF for efficient native execution
        $results = $query
            ->selectRaw('devices.name as device_name, SUM(TIMESTAMPDIFF(MINUTE, sessions.started_at, sessions.ended_at)) as total_playtime_minutes')
            ->groupBy('devices.id', 'devices.name')
            ->orderBy('total_playtime_minutes', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * Centralized optimized filtering using direct Joins
     */
    protected function applyFilters($query, Request $request): void
    {
        // Exclude deleted devices
        $query->whereNull('devices.deleted_at');

        if ($request->filled('start_date')) {
            $query->whereDate('sessions.started_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('sessions.started_at', '<=', $request->end_date);
        }

        if ($request->filled('branch_id')) {
            $query->where('devices.branch_id', $request->branch_id);
        }
    }
}

<?php

namespace App\Domains\Reports\Controllers\Api;

use App\Domains\Reports\Services\ReportingService;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ReportingService $reportingService
    ) {}

    /**
     * Display report dashboard.
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Get the high-level financial overview.
     */
    public function financialOverview(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $data = $this->reportingService->getFinancialSummary($filters);

        return $this->success($data);
    }

    /**
     * Get top devices and products.
     */
    public function leaderboard(): JsonResponse
    {
        return $this->success([
            'top_devices' => $this->reportingService->getTopDevices(),
            'top_products' => $this->reportingService->getTopProducts(),
        ]);
    }
}

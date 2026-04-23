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
            'to' => 'nullable|date',
        ]);

        $summary = $this->reportingService->getFinancialSummary($filters);
        $topDevices = $this->reportingService->getTopDevices();
        $topProducts = $this->reportingService->getTopProducts();

        return view('reports.index', compact('summary', 'topDevices', 'topProducts'));
    }
}

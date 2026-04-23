<?php

namespace App\Domains\AI\Controllers;

use App\Domains\AI\Services\AIInsightsService;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AIController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AIInsightsService $aiService
    ) {}

    /**
     * Get AI-driven business insights.
     */
    public function index(): JsonResponse
    {
        $insights = $this->aiService->generateInsights();

        return $this->success($insights, 'AI Insights generated successfully.');
    }
}

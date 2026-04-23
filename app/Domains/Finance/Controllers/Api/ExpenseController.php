<?php

namespace App\Domains\Finance\Controllers\Api;

use App\Domains\Finance\Resources\ExpenseResource;
use App\Domains\Finance\Services\ExpenseService;
use App\Enums\ExpenseType;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class ExpenseController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ExpenseService $expenseService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'type' => [new Enum(ExpenseType::class)],
            'from_date' => 'date',
            'to_date' => 'date',
        ]);

        $expenses = $this->expenseService->listExpenses($filters);

        return $this->success(ExpenseResource::collection($expenses));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => ['required', new Enum(ExpenseType::class)],
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        $expense = $this->expenseService->createExpense($data);

        return $this->success(new ExpenseResource($expense), 'Expense added successfully.', 201);
    }
}

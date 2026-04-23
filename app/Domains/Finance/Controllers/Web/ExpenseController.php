<?php

namespace App\Domains\Finance\Controllers\Web;

use App\Domains\Finance\Services\ExpenseService;
use App\Enums\ExpenseType;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function __construct(
        protected ExpenseService $expenseService
    ) {}

    /**
     * Display a listing of expenses.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'type' => ['nullable', new Enum(ExpenseType::class)],
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
        ]);

        $expenses = $this->expenseService->listExpenses($filters);

        return view('expenses.index', [
            'expenses' => $expenses,
            'types' => ExpenseType::cases(),
        ]);
    }

    /**
     * Store a newly created expense.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => ['required', new Enum(ExpenseType::class)],
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        $this->expenseService->createExpense($data);

        return redirect()->route('expenses.index')->with('success', __('notifications.expense_created'));
    }
}

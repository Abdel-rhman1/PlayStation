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
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
        ]);

        $expenses = $this->expenseService->listExpenses($filters);
        $categories = \App\Models\ExpenseCategory::all();

        return view('expenses.index', [
            'expenses' => $expenses,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        $this->expenseService->createExpense(auth()->id(), $data);

        return redirect()->route('expenses.index')->with('success', __('notifications.expense_created'));
    }

    public function update(Request $request, \App\Models\Expense $expense): RedirectResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', __('notifications.expense_updated'));
    }

    public function destroy(\App\Models\Expense $expense): RedirectResponse
    {
        $this->expenseService->deleteExpense($expense);

        return redirect()->route('expenses.index')->with('success', __('notifications.expense_deleted'));
    }
}

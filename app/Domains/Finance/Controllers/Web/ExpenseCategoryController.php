<?php

namespace App\Domains\Finance\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ExpenseCategory::all();
        return view('expenses.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ExpenseCategory::create($data);

        return redirect()->back()->with('success', 'Expense category created successfully.');
    }

    public function update(Request $request, ExpenseCategory $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($data);

        return redirect()->back()->with('success', 'Expense category updated successfully.');
    }

    public function destroy(ExpenseCategory $category): RedirectResponse
    {
        $category->delete();
        return redirect()->back()->with('success', 'Expense category deleted successfully.');
    }
}

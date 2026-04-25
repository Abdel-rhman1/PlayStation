<?php

namespace App\Domains\Finance\Services;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

class ExpenseService
{
    /**
     * List expenses with filters.
     */
    public function listExpenses(array $filters, int $perPage = 15)
    {
        $query = Expense::with('category');
        
        if (isset($filters['expense_category_id'])) {
            $query->where('expense_category_id', $filters['expense_category_id']);
        }

        if (isset($filters['from_date'])) {
            $query->where('date', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('date', '<=', $filters['to_date']);
        }

        return $query->latest('date')->paginate($perPage);
    }

    /**
     * Create a new expense.
     */
    public function createExpense(int $userId, array $data): Expense
    {
        $data['user_id'] = $userId;
        return Expense::create($data);
    }

    /**
     * Delete an expense.
     */
    public function deleteExpense(Expense $expense): bool
    {
        return $expense->delete();
    }
}

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
        $query = Expense::query();
        
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
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
    public function createExpense(array $data): Expense
    {
        return Expense::create($data);
    }
}

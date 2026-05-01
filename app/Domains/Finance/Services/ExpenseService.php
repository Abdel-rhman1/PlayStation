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
        $expense = Expense::create($data);

        // Notify about new expense
        $user = auth()->user();
        if ($user) {
            $user->notify(new \App\Notifications\SystemNotification([
                'title' => __('notifications.expense_recorded_title'),
                'message' => __('notifications.expense_recorded_msg', [
                    'amount' => number_format($expense->amount, 2),
                    'currency' => __('messages.currency_symbol'),
                    'category' => $expense->category->name ?? 'General'
                ]),
                'icon' => 'banknotes',
                'type' => 'warning',
                'action_url' => route('expenses.index'),
            ]));
        }

        return $expense;
    }

    /**
     * Delete an expense.
     */
    public function deleteExpense(Expense $expense): bool
    {
        return $expense->delete();
    }
}

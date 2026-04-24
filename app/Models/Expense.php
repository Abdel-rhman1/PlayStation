<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory, \App\Core\Tenancy\Traits\HasTenant, \App\Core\Traits\LogsActivity, \App\Core\Traits\HasShift;

    protected $fillable = ['tenant_id', 'user_id', 'shift_id', 'amount', 'type', 'description', 'date'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'type'   => \App\Enums\ExpenseType::class,
            'date'   => 'date',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory, \App\Core\Tenancy\Traits\HasTenant;

    protected $fillable = ['tenant_id', 'amount', 'type', 'description', 'date'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'type'   => \App\Enums\ExpenseType::class,
            'date'   => 'date',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory, \App\Core\Tenancy\Traits\HasTenant;

    protected $fillable = ['tenant_id', 'name'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}

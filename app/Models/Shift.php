<?php

namespace App\Models;

use App\Core\Tenancy\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    use HasTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'start_time',
        'end_time',
        'opening_balance',
        'closing_balance',
        'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'opening_balance' => 'float',
        'closing_balance' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get the currently active shift for a user.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'open');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Calculate session revenue for this shift.
     * Revenue belongs to the shift that ended the session.
     */
    public function getSessionRevenue(): float
    {
        return (float) Session::where('status', 'completed')
            ->where(function($q) {
                $q->where('ended_shift_id', $this->id)
                  ->orWhere(function($sq) {
                      $sq->whereNull('ended_shift_id')
                         ->where('shift_id', $this->id);
                  });
            })
            ->sum('cost');
    }
}

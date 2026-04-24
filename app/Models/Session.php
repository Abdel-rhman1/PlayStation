<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    use HasFactory, HasUuids, SoftDeletes, \App\Core\Tenancy\Traits\HasTenant, \App\Core\Traits\LogsActivity, \App\Core\Traits\HasShift;

    protected $fillable = [
        'tenant_id',
        'device_id',
        'user_id',
        'started_at',
        'ended_at',
        'total_price',
        'cost',
        'pricing_type',
        'status',
        'shift_id',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at'   => 'datetime',
            'cost'       => 'decimal:2',
            'total_price'=> 'decimal:2',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Duration in minutes (always positive, uses ended_at or now for active sessions).
     */
    public function getDurationAttribute(): int
    {
        $end = $this->ended_at ?? now();
        return (int) abs($this->started_at->diffInMinutes($end));
    }

    /**
     * Alias for ended_at to support legacy view references to end_time.
     */
    public function getEndTimeAttribute()
    {
        return $this->ended_at;
    }
}

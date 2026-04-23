<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    use HasFactory, HasUuids, SoftDeletes, \App\Core\Tenancy\Traits\HasTenant;

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
}

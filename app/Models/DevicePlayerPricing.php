<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevicePlayerPricing extends Model
{
    use HasFactory, HasUuids, \App\Core\Tenancy\Traits\HasTenant;

    protected $table = 'device_player_pricing';

    protected $fillable = [
        'tenant_id',
        'device_id',
        'player_count',
        'price_per_hour',
    ];

    protected function casts(): array
    {
        return [
            'player_count' => 'integer',
            'price_per_hour' => 'decimal:2',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}

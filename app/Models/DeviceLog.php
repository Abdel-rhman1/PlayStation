<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceLog extends Model
{
    use HasUuids, \App\Core\Tenancy\Traits\HasTenant;

    protected $fillable = [
        'tenant_id',
        'device_id',
        'action',
        'user_id',
    ];

    /**
     * Get the device associated with this log.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the user who executed the action (if applicable).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

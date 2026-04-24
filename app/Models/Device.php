<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory, HasUuids, SoftDeletes, \App\Core\Tenancy\Traits\HasTenant, \App\Core\Traits\LogsActivity, \App\Core\Traits\HasBranchAccess;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'ip_address',
        'hourly_rate',
        'fixed_rate',
        'status',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(DeviceLog::class);
    }

    public function activeSession()
    {
        return $this->hasOne(Session::class)->whereNull('ended_at')->latest();
    }

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\DeviceStatus::class,
            'hourly_rate' => 'decimal:2',
            'fixed_rate' => 'decimal:2',
        ];
    }
}

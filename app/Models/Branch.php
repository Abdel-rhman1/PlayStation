<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory, HasUuids, SoftDeletes, \App\Core\Tenancy\Traits\HasTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'address',
        'phone',
        'is_active',
    ];

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}

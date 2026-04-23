<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_active', 'plan_id', 'subscription_ends_at'];

    protected $casts = [
        'subscription_ends_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function hasActiveSubscription(): bool
    {
        if (!$this->plan_id || !$this->subscription_ends_at) {
            return false;
        }

        return $this->subscription_ends_at->isFuture();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_active', 'plan_id', 'subscription_ends_at', 'trial_ends_at'];

    protected $casts = [
        'subscription_ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function owner()
    {
        return $this->hasOne(User::class)->oldestOfMany();
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function hasActiveSubscription(): bool
    {
        if ($this->onTrial()) {
            return true;
        }

        if (!$this->plan_id || !$this->subscription_ends_at) {
            return false;
        }

        return $this->subscription_ends_at->isFuture();
    }

    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }
}

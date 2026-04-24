<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    protected $fillable = [
        'session_id',
        'device_price',
        'orders_total',
        'grand_total',
        'snapshot'
    ];

    protected $casts = [
        'snapshot' => 'array',
        'device_price' => 'float',
        'orders_total' => 'float',
        'grand_total' => 'float',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }
}

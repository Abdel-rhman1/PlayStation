<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, \App\Core\Tenancy\Traits\HasTenant, \App\Core\Traits\LogsActivity, \App\Core\Traits\HasShift;

    protected $fillable = ['tenant_id', 'session_id', 'user_id', 'shift_id', 'total_price', 'status'];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

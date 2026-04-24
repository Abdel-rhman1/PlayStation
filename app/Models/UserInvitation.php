<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInvitation extends Model
{
    use \App\Core\Tenancy\Traits\HasTenant;

    protected $fillable = ['tenant_id', 'role_id', 'email', 'token', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }
}

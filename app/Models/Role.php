<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use \App\Core\Tenancy\Traits\HasTenant;

    protected $fillable = ['tenant_id', 'name', 'has_full_branch_access', 'description'];

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }

    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(\App\Models\Permission::class);
    }
}

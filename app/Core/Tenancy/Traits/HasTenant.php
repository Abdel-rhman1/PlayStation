<?php

namespace App\Core\Tenancy\Traits;

use App\Core\Tenancy\Scopes\TenantScope;
use App\Core\Tenancy\TenantManager;
use Illuminate\Database\Eloquent\Model;

trait HasTenant
{
    /**
     * Boot the trait.
     */
    public static function bootHasTenant(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function (Model $model) {
            $tenantManager = app(TenantManager::class);
            
            if ($tenantManager->hasTenant()) {
                $model->tenant_id = $tenantManager->getTenantId();
            }
        });
    }

    /**
     * Get the tenant associated with the model.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}

<?php

namespace App\Core\Tenancy;

use App\Models\Tenant;

class TenantManager
{
    /**
     * The current tenant instance.
     */
    protected ?Tenant $tenant = null;

    /**
     * Set the current tenant.
     */
    public function setTenant(Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    /**
     * Get the current tenant.
     */
    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    /**
     * Get the current tenant ID.
     */
    public function getTenantId(): ?int
    {
        return $this->tenant?->id;
    }

    /**
     * Check if a tenant is set.
     */
    public function hasTenant(): bool
    {
        return !is_null($this->tenant);
    }
}

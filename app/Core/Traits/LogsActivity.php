<?php

namespace App\Core\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Automatically boot the trait to register observers.
     */
    protected static function bootLogsActivity()
    {
        foreach (static::getAuditEvents() as $event) {
            static::$event(function (Model $model) use ($event) {
                static::recordAuditLog($model, $event);
            });
        }
    }

    /**
     * Get the events to be audited.
     */
    protected static function getAuditEvents(): array
    {
        return ['created', 'updated', 'deleted'];
    }

    /**
     * Create the audit log record.
     */
    protected static function recordAuditLog(Model $model, string $event)
    {
        AuditLog::create([
            'tenant_id' => $model->tenant_id ?? app(\App\Core\Tenancy\TenantManager::class)->getTenantId(),
            'user_id' => Auth::id(),
            'action' => $event,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'payload' => $event === 'updated' ? [
                'old' => array_intersect_key($model->getOriginal(), $model->getDirty()),
                'new' => $model->getDirty(),
            ] : $model->toArray(),
        ]);
    }
}

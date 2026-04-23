<?php

namespace App\DTOs;

use App\Models\Session;

class SessionResponseDTO
{
    public function __construct(
        public readonly string $sessionId,
        public readonly string $deviceId,
        public readonly string $status,
        public readonly ?string $startedAt,
        public readonly ?string $endedAt = null,
        public readonly ?float $cost = null,
    ) {
    }

    public static function fromModel(Session $session): self
    {
        return new self(
            sessionId: $session->id,
            deviceId: $session->device_id,
            status: $session->status,
            startedAt: $session->started_at?->toIso8601String(),
            endedAt: $session->ended_at?->toIso8601String(),
            cost: $session->cost ? (float) $session->cost : null,
        );
    }

    public function toArray(): array
    {
        return [
            'session_id' => $this->sessionId,
            'device_id' => $this->deviceId,
            'status' => $this->status,
            'started_at' => $this->startedAt,
            'ended_at' => $this->endedAt,
            'cost' => $this->cost,
        ];
    }
}

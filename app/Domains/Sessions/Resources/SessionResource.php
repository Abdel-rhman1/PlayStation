<?php

namespace App\Domains\Sessions\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'device_id' => $this->device_id,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'duration_minutes' => $this->started_at->diffInMinutes($this->ended_at ?? now()),
            'cost' => $this->cost,
            'status' => $this->status,
            'pricing_type' => $this->pricing_type,
        ];
    }
}

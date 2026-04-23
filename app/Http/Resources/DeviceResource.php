<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
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
            'branch_id' => $this->branch_id,
            'name' => $this->name,
            'ip_address' => $this->ip_address,
            'hourly_rate' => (float) $this->hourly_rate,
            'fixed_rate' => $this->fixed_rate ? (float) $this->fixed_rate : null,
            'status' => $this->status,
        ];
    }
}

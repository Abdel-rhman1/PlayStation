<?php

namespace App\Domains\Inventory\Requests;

use App\Enums\DeviceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'ip_address' => 'sometimes|nullable|ip',
            'hourly_rate' => 'sometimes|required|numeric|min:0',
            'fixed_rate' => 'sometimes|nullable|numeric|min:0',
            'player_pricing' => 'sometimes|nullable|array',
            'player_pricing.2' => 'sometimes|nullable|numeric|min:0',
            'player_pricing.4' => 'sometimes|nullable|numeric|min:0',
        ];
    }
}

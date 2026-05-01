<?php

namespace App\Domains\Inventory\Requests;

use App\Enums\DeviceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'ip_address' => 'nullable|ip',
            'hourly_rate' => 'required|numeric|min:0',
            'fixed_rate' => 'nullable|numeric|min:0',
            'player_pricing' => 'nullable|array',
            'player_pricing.2' => 'nullable|numeric|min:0',
            'player_pricing.4' => 'nullable|numeric|min:0',
        ];
    }
}

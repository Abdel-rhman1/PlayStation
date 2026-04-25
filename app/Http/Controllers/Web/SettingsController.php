<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tenant = $user->tenant;
        $plan = $tenant->plan;
        
        // Dynamic Slot Usage
        $deviceCount = Device::count(); // Multi-tenant scope should handle this
        $deviceLimit = $plan->device_limit ?? 50;
        $usagePercent = $deviceLimit > 0 ? ($deviceCount / $deviceLimit) * 100 : 0;

        return view('settings.index', compact(
            'user',
            'tenant',
            'plan',
            'deviceCount',
            'deviceLimit',
            'usagePercent'
        ));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update($request->only('name', 'email'));

        return back()->with('success', 'Profile updated successfully.');
    }
}

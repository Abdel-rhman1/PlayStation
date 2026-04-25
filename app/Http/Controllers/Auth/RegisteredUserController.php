<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_slug' => ['nullable', 'string', 'max:30', 'alpha_dash', 'unique:tenants,slug'],
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $user = DB::transaction(function () use ($request) {
            $slug = $request->shop_slug ?: \Illuminate\Support\Str::slug($request->shop_name);
            
            // Ensure slug is unique if generated
            if (!$request->filled('shop_slug')) {
                $originalSlug = $slug;
                $count = 1;
                while (\App\Models\Tenant::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
            }

            // 1. Create the Tenant
            $tenant = \App\Models\Tenant::create([
                'name' => $request->shop_name,
                'slug' => $slug,
                'plan_id' => $request->plan_id,
                'subscription_ends_at' => now()->addDays(14), // 14 day trial
                'is_active' => true,
            ]);

            // 2. Create the Owner Role for this tenant
            $ownerRole = \App\Models\Role::create([
                'tenant_id' => $tenant->id,
                'name' => 'owner',
                'description' => 'System Owner with full access',
                'has_full_branch_access' => true,
            ]);

            // 3. Grant all permissions to the owner role
            $allPermissions = \App\Models\Permission::all();
            $ownerRole->permissions()->sync($allPermissions->pluck('id'));

            // 4. Create the Owner User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
                'role_id' => $ownerRole->id, // Assuming role_id column exists on users (as seen in HasPermissions check)
            ]);
            
            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

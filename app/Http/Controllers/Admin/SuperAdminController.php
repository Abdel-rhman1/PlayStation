<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\User;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('is_active', true)->count();
        $totalUsers = User::count();
        $totalRevenue = Tenant::join('plans', 'tenants.plan_id', '=', 'plans.id')->sum('plans.price');
        $latestTenants = Tenant::with('plan')->latest()->take(5)->get();
        
        // Calculate dynamic growth percentages (Comparing with last 30 days)
        $previousTenants = Tenant::where('created_at', '<', now()->subDays(30))->count();
        $tenantGrowth = $previousTenants > 0 ? (($totalTenants - $previousTenants) / $previousTenants) * 100 : 100;

        $previousUsers = User::where('created_at', '<', now()->subDays(30))->count();
        $userGrowth = $previousUsers > 0 ? (($totalUsers - $previousUsers) / $previousUsers) * 100 : 100;

        // Alerts: Subscriptions expiring within 48 hours
        $expiringCount = Tenant::whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '<', now()->addHours(48))
            ->where('subscription_ends_at', '>', now())
            ->count();

        $tenancyGrowth = Tenant::select(DB::raw('count(*) as count'), DB::raw('DATE(created_at) as date'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        return view('admin.dashboard', compact(
            'totalTenants',
            'activeTenants',
            'totalUsers',
            'totalRevenue',
            'latestTenants',
            'tenancyGrowth',
            'tenantGrowth',
            'userGrowth',
            'expiringCount'
        ));
    }

    public function tenants()
    {
        $tenants = Tenant::with('plan')->latest()->paginate(10);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function createTenant()
    {
        $plans = Plan::all();
        return view('admin.tenants.create', compact('plans'));
    }

    public function storeTenant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug',
            'plan_id' => 'required|exists:plans,id',
            'subscription_ends_at' => 'nullable|date',
        ]);

        Tenant::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'plan_id' => $request->plan_id,
            'subscription_ends_at' => $request->subscription_ends_at ?: now()->addYear(),
            'is_active' => true,
        ]);

        return redirect()->route('admin.tenants')->with('success', 'Tenant created successfully.');
    }

    public function editTenant(Tenant $tenant)
    {
        $plans = Plan::all();
        return view('admin.tenants.edit', compact('tenant', 'plans'));
    }

    public function updateTenant(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug,' . $tenant->id,
            'plan_id' => 'required|exists:plans,id',
            'subscription_ends_at' => 'nullable|date',
        ]);

        $tenant->update($request->all());

        return redirect()->route('admin.tenants')->with('success', 'Tenant updated successfully.');
    }

    public function toggleTenantStatus(Tenant $tenant)
    {
        $tenant->update(['is_active' => !$tenant->is_active]);
        return back()->with('success', 'Tenant status updated successfully.');
    }

    public function deleteTenant(Tenant $tenant)
    {
        // Add safeguard: Don't allow accidental deletion without confirmation logic in frontend
        $tenant->delete();
        return redirect()->route('admin.tenants')->with('success', 'Tenant deleted successfully.');
    }

    public function plans()
    {
        $plans = Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    public function createPlan()
    {
        return view('admin.plans.create');
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'device_limit' => 'required|integer|min:1',
            'billing_cycle_days' => 'required|integer|min:1',
        ]);

        Plan::create($request->all());

        return redirect()->route('admin.plans')->with('success', 'Plan created successfully.');
    }

    public function editPlan(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function updatePlan(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'device_limit' => 'required|integer|min:1',
            'billing_cycle_days' => 'required|integer|min:1',
        ]);

        $plan->update($request->all());

        return redirect()->route('admin.plans')->with('success', 'Plan updated successfully.');
    }

    public function deletePlan(Plan $plan)
    {
        if ($plan->tenants()->count() > 0) {
            return back()->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $plan->delete();
        return redirect()->route('admin.plans')->with('success', 'Plan deleted successfully.');
    }

    public function users()
    {
        $users = User::with('tenant')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $tenants = Tenant::all();
        return view('admin.users.create', compact('tenants'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'tenant_id' => 'nullable|exists:tenants,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $request->tenant_id, // null means Super Admin
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $tenants = Tenant::all();
        return view('admin.users.edit', compact('user', 'tenants'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'tenant_id' => 'nullable|exists:tenants,id',
        ]);

        $data = $request->only(['name', 'email', 'tenant_id']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function roles()
    {
        $roles = \App\Models\Role::with('tenant')->latest()->paginate(20);
        return view('admin.roles.index', compact('roles'));
    }

    public function createRole()
    {
        $tenants = Tenant::all();
        $permissions = \App\Models\Permission::all();
        return view('admin.roles.create', compact('tenants', 'permissions'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tenant_id' => 'nullable|exists:tenants,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = \App\Models\Role::create([
            'name' => $request->name,
            'tenant_id' => $request->tenant_id,
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles')->with('success', 'Role created successfully.');
    }

    public function editRole(\App\Models\Role $role)
    {
        $tenants = Tenant::all();
        $permissions = \App\Models\Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'tenants', 'permissions', 'rolePermissions'));
    }

    public function updateRole(Request $request, \App\Models\Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tenant_id' => 'nullable|exists:tenants,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update($request->only(['name', 'tenant_id', 'description']));
        
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles')->with('success', 'Role updated successfully.');
    }

    public function deleteRole(\App\Models\Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role with assigned users.');
        }

        $role->delete();
        return redirect()->route('admin.roles')->with('success', 'Role deleted successfully.');
    }

    public function reports()
    {
        $tenants = Tenant::with(['plan', 'owner'])->latest()->paginate(50);
        $revenueByMonth = Tenant::join('plans', 'tenants.plan_id', '=', 'plans.id')
            ->selectRaw('MONTH(tenants.created_at) as month, SUM(plans.price) as total')
            ->groupBy('month')
            ->get();

        $totalRevenue = Tenant::join('plans', 'tenants.plan_id', '=', 'plans.id')->sum('plans.price');
        $averageMonthly = $revenueByMonth->count() > 0 ? $totalRevenue / $revenueByMonth->count() : 0;
        $projectedRevenue = $totalRevenue + $averageMonthly; // Very simple projection

        return view('admin.reports.index', compact('tenants', 'revenueByMonth', 'projectedRevenue'));
    }
}

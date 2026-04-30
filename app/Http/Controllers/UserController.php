<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        // Global scope automatically handles tenant_id filtering
        $users = User::with('role')
            ->latest()
            ->paginate(20);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role_id'  => ['required', Rule::exists('roles', 'id')->where('tenant_id', auth()->user()->tenant_id)],
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'tenant_id' => auth()->user()->tenant_id,
            'role_id'   => $validated['role_id'],
        ]);

        // Notify about new user
        auth()->user()->notify(new \App\Notifications\SystemNotification([
            'title' => 'New User Created',
            'message' => "User account for {$user->name} has been created successfully.",
            'icon' => 'user-plus',
            'type' => 'success',
            'action_url' => route('users.index'),
        ]));

        return redirect()->route('users.index')
            ->with('success', __('notifications.user_created'));
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role_id'  => ['required', Rule::exists('roles', 'id')->where('tenant_id', auth()->user()->tenant_id)],
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            ...($validated['password'] ? ['password' => Hash::make($validated['password'])] : []),
        ]);

        return redirect()->route('users.index')
            ->with('success', __('notifications.user_updated'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', __('notifications.user_self_delete'));
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', __('notifications.user_deleted'));
    }
}

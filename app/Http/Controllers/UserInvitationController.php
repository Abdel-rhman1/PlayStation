<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class UserInvitationController extends Controller
{
    /**
     * Show the invitation form (owner only)
     */
    public function create(): View
    {
        $roles = Role::all();
        return view('invitations.create', compact('roles'));
    }

    /**
     * Store the invitation and send email (owner only)
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email'   => ['required', 'email', 'max:255', 'unique:users,email'],
            'role_id' => ['required', Rule::exists('roles', 'id')->where('tenant_id', auth()->user()->tenant_id)],
        ]);

        $token = Str::random(40);

        UserInvitation::updateOrCreate(
            ['email' => $validated['email']],
            [
                'role_id' => $validated['role_id'],
                'token'   => $token,
                'completed_at' => null,
            ]
        );

        // Sending a literal generic email since this is internal
        // Ideally use Mailable, but we'll simulate for now or use a basic Mailable.
        // We'll create a simple mailable class next.
        Mail::to($validated['email'])->send(new \App\Mail\TeamInvitation(
            $validated['email'], 
            $token, 
            auth()->user()->tenant->name ?? 'our team',
            auth()->user()->name
        ));

        return redirect()->route('users.index')
            ->with('success', __('invitations.sent_successfully'));
    }

    /**
     * Show the accept invitation form (guest)
     */
    public function accept(string $token): View
    {
        $invitation = UserInvitation::where('token', $token)
            ->whereNull('completed_at')
            ->firstOrFail();

        return view('invitations.accept', compact('invitation'));
    }

    /**
     * Process the accepted invitation (guest)
     */
    public function process(Request $request, string $token): RedirectResponse
    {
        $invitation = UserInvitation::where('token', $token)
            ->whereNull('completed_at')
            ->firstOrFail();

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Manually authenticate to the tenant context or pass tenant directly
        // Because this is guest route, we must bypass global scope slightly for tenant assignment
        
        $user = new User([
            'name'      => $validated['name'],
            'email'     => $invitation->email,
            'password'  => Hash::make($validated['password']),
        ]);
        
        // Temporarily assign tenant to the newly built user
        $user->tenant_id = $invitation->tenant_id;
        $user->role_id = $invitation->role_id;
        $user->save();

        $invitation->update(['completed_at' => now()]);

        auth()->login($user);

        return redirect()->route('dashboard')->with('success', __('invitations.welcome'));
    }
}

<?php

namespace App\Domains\Sessions\Controllers\Web;

use App\Domains\Sessions\Services\SessionService;
use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Domains\Sessions\Exceptions\DeviceNotAvailableException;
use App\Domains\Sessions\Exceptions\DeviceNotActiveException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Models\Session;

class WebSessionController extends Controller
{
    public function __construct(
        protected SessionService $sessionService
    ) {}

    /**
     * Display a listing of sessions.
     */
    public function index(Request $request)
    {
        // For the sessions journal
        $query = \App\Models\Session::with(['device', 'user']);

        if ($request->filled('from')) {
            $query->whereDate('started_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('started_at', '<=', $request->to);
        }

        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                  ->orWhereHas('device', function($dq) use ($searchTerm) {
                      $dq->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('user', function($uq) use ($searchTerm) {
                      $uq->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $sessions = $query->latest()->paginate(15);
        $devices = \App\Models\Device::all();
        $users = \App\Models\User::all();

        return view('sessions.index', compact('sessions', 'devices', 'users'));
    }

    /**
     * Start a session for a device.
     */
    public function start(Device $device, Request $request): RedirectResponse
    {
        try {
            $this->sessionService->startSession($device, auth()->id());
            return back()->with('success', __('notifications.session_started'));
        } catch (DeviceNotAvailableException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to start session: ' . $e->getMessage());
        }
    }

    /**
     * Stop a session for a device.
     */
    public function stop(Device $device, Request $request): RedirectResponse
    {
        try {
            $session = $this->sessionService->stopSession($device, auth()->id());
            
            return redirect()->route('sessions.receipt', $session->id)
                ->with('success', __('notifications.session_stopped'));
        } catch (DeviceNotActiveException $e) {
            return back()->with('error', $e->getMessage());
        } catch (BadRequestException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to stop session: ' . $e->getMessage());
        }
    }

    /**
     * Show the receipt for a specific session.
     */
    public function receipt(Session $session, \App\Services\Sessions\SessionBillingService $billingService)
    {
        $data = $billingService->generateReceiptData($session);
        return view('receipts.session', compact('data'));
    }

    /**
     * Show live session details.
     */
    public function show(Session $session)
    {
        $session->load(['device', 'orders.items.product', 'user']);
        return view('sessions.show', compact('session'));
    }

    /**
     * Get live data for a session (JSON).
     */
    public function data(Session $session)
    {
        return response()->json([
            'started_at' => $session->started_at->toIso8601String(),
            'unpaid_orders_total' => (float) $session->orders()->unpaid()->sum('total_price'),
            'paid_orders_total' => (float) $session->orders()->paid()->sum('total_price'),
            'status' => $session->status,
        ]);
    }
}

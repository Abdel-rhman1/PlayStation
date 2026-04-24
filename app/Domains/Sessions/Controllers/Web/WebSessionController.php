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
    public function index()
    {
        // For the sessions journal
        $sessions = \App\Models\Session::with(['device', 'user'])
            ->latest()
            ->paginate(15);

        return view('sessions.index', compact('sessions'));
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
}

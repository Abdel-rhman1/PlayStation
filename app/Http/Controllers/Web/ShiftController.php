<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Staff\ShiftService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function __construct(
        protected ShiftService $shiftService
    ) {}

    /**
     * Redirect to the correct page based on shift status.
     */
    public function index()
    {
        $currentShift = Auth::user()->shifts()->active()->first();
        return $currentShift 
            ? redirect()->route('shifts.active') 
            : redirect()->route('shifts.start');
    }

    /**
     * Start Shift Page.
     */
    public function startPage()
    {
        if (Auth::user()->shifts()->active()->exists()) {
            return redirect()->route('shifts.active');
        }
        return view('shifts.start');
    }

    /**
     * Active Shift Page.
     */
    public function activePage()
    {
        $currentShift = Auth::user()->shifts()->active()->first();
        if (!$currentShift) {
            return redirect()->route('shifts.start');
        }
        return view('shifts.active', compact('currentShift'));
    }

    /**
     * Start a new shift.
     */
    public function start(Request $request)
    {
        $request->validate([
            'opening_balance' => 'nullable|numeric|min:0',
        ]);

        try {
            $this->shiftService->startShift(Auth::user(), $request->opening_balance ?? 0);
            
            return redirect()->route('dashboard')
                ->with('success', 'Shift started successfully. Good luck with your work!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Close the active shift.
     */
    public function close(Request $request)
    {
        $request->validate([
            'closing_balance' => 'required|numeric|min:0',
        ]);

        try {
            $shift = $this->shiftService->closeShift(Auth::user(), $request->closing_balance);
            
            return redirect()->route('shifts.summary', $shift)
                ->with('success', 'Shift closed successfully. Here is your final summary.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show shift summary.
     */
    public function show(Shift $shift)
    {
        $this->authorize('view', $shift);
        $summary = $this->shiftService->getShiftSummary($shift);
        return view('shifts.show', compact('shift', 'summary'));
    }

    /**
     * Print shift report.
     */
    public function print(Shift $shift)
    {
        $this->authorize('view', $shift);
        $summary = $this->shiftService->calculateShiftSummary($shift);
        return view('shifts.report', compact('shift', 'summary'));
    }
}

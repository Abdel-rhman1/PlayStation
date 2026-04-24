<?php

namespace App\Services\Staff;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ShiftAlreadyActiveException;
use App\Exceptions\NoActiveShiftException;
use Illuminate\Support\Facades\Auth;

class ShiftService
{
    /**
     * Start a new shift for the user.
     */
    public function startShift(User $user, float $openingBalance = 0): Shift
    {
        // Check if user already has an active shift
        if ($user->shifts()->active()->exists()) {
            throw new \Exception("User already has an active shift.");
        }

        return Shift::create([
            'tenant_id' => $user->tenant_id,
            'user_id' => $user->id,
            'start_time' => now(),
            'opening_balance' => $openingBalance,
            'status' => 'open'
        ]);
    }

    /**
     * Close the active shift for the user.
     */
    public function closeShift(User $user, float $closingBalance = 0): Shift
    {
        $shift = $user->shifts()->active()->first();

        if (!$shift) {
            throw new \Exception("No active shift found for this user.");
        }

        $shift->update([
            'end_time' => now(),
            'closing_balance' => $closingBalance,
            'status' => 'closed'
        ]);

        return $shift;
    }

    /**
     * Return active shift for authenticated user.
     * @throws NoActiveShiftException
     */
    public function getCurrentShift(): Shift
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Exception("Unauthenticated.");
        }

        $shift = $user->shifts()->active()->first();

        if (!$shift) {
            throw new NoActiveShiftException("You must open a shift before performing this action.");
        }

        return $shift;
    }

    /**
     * Get a financial summary for a shift.
     */
    public function getShiftSummary(Shift $shift): array
    {
        $sessionRevenue = $shift->sessions()->sum('total_price');
        $orderRevenue = $shift->orders()->sum('total_price');
        $expensesTotal = $shift->expenses()->sum('amount');

        $totalRevenue = $sessionRevenue + $orderRevenue;
        $netCash = ($shift->opening_balance + $totalRevenue) - $expensesTotal;

        return [
            'opening_balance' => (float)$shift->opening_balance,
            'session_revenue' => (float)$sessionRevenue,
            'order_revenue'   => (float)$orderRevenue,
            'total_revenue'   => (float)$totalRevenue,
            'expenses_total'  => (float)$expensesTotal,
            'expected_cash'   => (float)$netCash,
            'actual_cash'     => (float)$shift->closing_balance,
            'difference'      => (float)($shift->closing_balance - $netCash),
        ];
    }

    /**
     * Calculate shift summary with specific financial breakdown.
     */
    public function calculateShiftSummary(Shift $shift): array
    {
        $sessionsTotal = (float) $shift->sessions()->sum('total_price');
        $ordersTotal   = (float) $shift->orders()->sum('total_price');
        $expensesTotal = (float) $shift->expenses()->sum('amount');

        return [
            'sessions_total' => $sessionsTotal,
            'orders_total'   => $ordersTotal,
            'expenses_total' => $expensesTotal,
            'net_total'      => $sessionsTotal + $ordersTotal - $expensesTotal,
        ];
    }
}

<?php

namespace App\Services\Sessions;

use App\Models\Session;
use Carbon\Carbon;

class SessionBillingService
{
    /**
     * Calculate the full statement for a session.
     */
    public function calculateTotal(Session $session): array
    {
        $devicePrice = $this->calculateDevicePrice($session);
        
        $paidOrdersTotal = (float) $session->orders()->paid()->sum('total_price');
        $unpaidOrdersTotal = (float) $session->orders()->unpaid()->sum('total_price');

        return [
            'device_price' => (float)$devicePrice,
            'paid_orders_total' => $paidOrdersTotal,
            'unpaid_orders_total' => $unpaidOrdersTotal,
            'grand_total'  => (float)($devicePrice + $paidOrdersTotal + $unpaidOrdersTotal),
            'due_amount' => (float)($devicePrice + $unpaidOrdersTotal),
        ];
    }

    /**
     * Calculate real-time totals for an active session including payment status breakdown.
     */
    public function calculateRealTimeTotals(Session $session): array
    {
        return $this->calculateTotal($session);
    }

    /**
     * Generate structured receipt data for a session and its linked orders.
     */
    public function generateReceiptData(Session $session): array
    {
        $session->load(['device', 'orders.items.product']);
        $billing = $this->calculateTotal($session);

        $endTime = $session->ended_at ?: now();
        $duration = $session->started_at->diff($endTime);

        $paidOrdersList = $session->orders->where('payment_status', 'paid')->flatMap(function ($order) {
            return $order->items->map(function ($item) {
                return [
                    'product_name' => $item->product->name,
                    'quantity'     => $item->quantity,
                    'unit_price'   => (float)$item->unit_price,
                    'total_price'  => (float)$item->total_price,
                ];
            });
        });

        $unpaidOrdersList = $session->orders->where('payment_status', 'unpaid')->flatMap(function ($order) {
            return $order->items->map(function ($item) {
                return [
                    'product_name' => $item->product->name,
                    'quantity'     => $item->quantity,
                    'unit_price'   => (float)$item->unit_price,
                    'total_price'  => (float)$item->total_price,
                ];
            });
        });

        return [
            'device' => [
                'name'       => $session->device->name,
                'start_time' => $session->started_at->format('Y-m-d H:i:s'),
                'end_time'   => $endTime->format('Y-m-d H:i:s'),
                'duration'   => sprintf('%dh %dm %ds', $duration->h, $duration->i, $duration->s),
                'price'      => $billing['device_price'],
            ],
            'paid_orders' => [
                'items' => $paidOrdersList->toArray(),
                'total' => $billing['paid_orders_total'],
            ],
            'unpaid_orders' => [
                'items' => $unpaidOrdersList->toArray(),
                'total' => $billing['unpaid_orders_total'],
            ],
            'due_amount' => $billing['due_amount'],
            'grand_total' => $billing['grand_total'],
            'currency'    => 'EGP',
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    public function calculateDevicePrice(Session $session): float
    {
        $startTime = $session->started_at;
        $endTime = $session->ended_at ?: now();

        $durationInMinutes = max(0, $startTime->diffInMinutes($endTime, true));
        $durationInHours = $durationInMinutes / 60;

        $device = $session->device;
        
        // Base Logic: Fixed entry fee + (Hourly Rate * Hours)
        $hourlyRate = (float) $device->hourly_rate;
        $fixedRate = (float) $device->fixed_rate;

        return round($fixedRate + ($durationInHours * $hourlyRate), 2);
    }
}

<?php

namespace App\Domains\POS\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Device;
use Illuminate\Support\Facades\DB;

class POSService
{
    /**
     * Create a new order with multiple items and link to an active session.
     */
    public function createOrder(int $userId, array $items, ?string $deviceId = null): Order
    {
        if (!$deviceId) {
            throw new \Exception("A device must be selected to create an order.");
        }

        $device = Device::findOrFail($deviceId);
        $activeSession = $device->activeSession;

        if (!$activeSession) {
            throw new \Exception("The selected device does not have an active session.");
        }

        $sessionId = $activeSession->id;

        $tenantId = $device->tenant_id;

        return DB::transaction(function () use ($userId, $items, $sessionId, $tenantId) {
            $order = Order::create([
                'tenant_id' => $tenantId,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'status' => 'pending',
                'total_price' => 0,
            ]);

            $totalPrice = 0;

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemTotal = $product->price * $item['quantity'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $itemTotal,
                ]);

                $totalPrice += $itemTotal;
            }

            $order->update(['total_price' => $totalPrice]);

            return $order->load('items.product');
        });
    }

    /**
     * Mark an order as paid.
     */
    public function markAsPaid(Order $order): Order
    {
        $order->update(['status' => 'paid']);
        return $order;
    }
}

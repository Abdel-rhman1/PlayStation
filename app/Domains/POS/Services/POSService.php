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
    public function createOrder(int $userId, array $items, ?string $deviceId = null, bool $isPaid = false): Order
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

        return DB::transaction(function () use ($userId, $items, $sessionId, $tenantId, $isPaid, $device) {
            $order = Order::create([
                'tenant_id' => $tenantId,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'status' => $isPaid ? 'paid' : 'pending',
                'payment_status' => $isPaid ? 'paid' : 'unpaid',
                'paid_at' => $isPaid ? now() : null,
                'total_price' => 0,
            ]);

            $totalPrice = 0;

            foreach ($items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception(__('products.insufficient_stock', [
                        'name' => $product->name,
                        'stock' => $product->stock
                    ]));
                }

                $itemTotal = $product->price * $item['quantity'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $itemTotal,
                ]);

                // Decrease stock
                $product->decrement('stock', $item['quantity']);

                $totalPrice += $itemTotal;
            }

            $order->update(['total_price' => $totalPrice]);

            // Notify about new order
            $user = auth()->user();
            if ($user) {
                $user->notify(new \App\Notifications\SystemNotification([
                    'title' => __('notifications.order_created_title'),
                    'message' => __('notifications.order_created_msg', [
                        'amount' => number_format($totalPrice, 2),
                        'currency' => __('messages.currency_symbol'),
                        'device' => $device->name
                    ]),
                    'icon' => 'shopping-cart',
                    'type' => 'success',
                    'action_url' => route('orders.index'),
                ]));
            }

            return $order->load('items.product');
        });
    }

    /**
     * Mark an order as paid.
     */
    public function markAsPaid(Order $order): Order
    {
        if ($order->payment_status === 'paid') {
            throw new \Exception("Order is already paid.");
        }

        $order->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
        return $order;
    }
}

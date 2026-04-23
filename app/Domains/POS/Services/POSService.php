<?php

namespace App\Domains\POS\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class POSService
{
    /**
     * Create a new order with multiple items.
     */
    public function createOrder(int $userId, array $items): Order
    {
        return DB::transaction(function () use ($userId, $items) {
            $order = Order::create([
                'user_id' => $userId,
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

<?php

namespace App\Domains\POS\Controllers\Api;

use App\Domains\POS\Resources\OrderResource;
use App\Domains\POS\Services\POSService;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected POSService $posService
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = $this->posService->createOrder(
            $request->user()->id,
            $request->get('items')
        );

        return $this->success(new OrderResource($order), 'Order created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {
        return $this->success(new OrderResource($order->load('items.product')));
    }

    /**
     * Mark an order as paid.
     */
    public function pay(Order $order): JsonResponse
    {
        $updatedOrder = $this->posService->markAsPaid($order);
        return $this->success(new OrderResource($updatedOrder), 'Order marked as paid.');
    }
}

<?php

namespace App\Domains\POS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Device;
use App\Domains\POS\Services\POSService;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function __construct(
        protected POSService $posService
    ) {}

    public function index()
    {
        $products = Product::with('category')->get();
        $devices = Device::where('status', 'IN_USE')->get();
        return view('pos.index', compact('products', 'devices'));
    }

    public function store(Request $request)
    {
        // This handles the form submission from the Blade POS
        $cart = json_decode($request->input('cart_data'), true);
        
        if (empty($cart)) {
            return back()->with('error', 'Cart is empty.');
        }

        try {
            $items = array_map(fn($item) => [
                'product_id' => $item['id'],
                'quantity' => $item['qty']
            ], $cart);

            $this->posService->createOrder(
                auth()->id(), 
                $items, 
                $request->input('device_id'),
                (bool) $request->input('is_paid', false)
            );

            return back()->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }
}

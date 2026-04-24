<?php

namespace App\Domains\POS\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Domains\POS\Services\POSService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected POSService $posService
    ) {}

    public function index(): View
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product', 'session.device']);
        return view('orders.show', compact('order'));
    }

    public function export()
    {
        $fileName = 'orders_export_' . now()->format('Y-m-d_His') . '.csv';
        $orders = Order::with('user')->latest()->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $columns = ['Order ID', 'Cashier', 'Item Count', 'Total Price', 'Status', 'Date'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name ?? 'System',
                    $order->items->count(),
                    number_format($order->total_price, 2),
                    $order->status,
                    $order->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

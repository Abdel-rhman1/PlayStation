<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Product;
use App\Models\Session;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function query(Request $request)
    {
        $q = $request->get('q');
        
        if (!$q) {
            return response()->json([]);
        }

        $devices = Device::where('name', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn($d) => [
                'title' => $d->name,
                'subtitle' => 'Device - ' . ($d->branch->name ?? 'Main'),
                'url' => route('devices.show', $d),
                'type' => 'device'
            ]);

        $products = Product::where('name', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'title' => $p->name,
                'subtitle' => 'Product - $' . number_format($p->price, 2),
                'url' => route('products.index'),
                'type' => 'product'
            ]);

        return response()->json($devices->merge($products));
    }
}

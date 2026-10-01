<?php

namespace App\Http\Controllers;

use App\Models\Product;

class KasirController extends Controller
{
    public function dashboard()
    {
        return view('kasir.dashboard', [
            'products' => Product::query()
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->orderBy('name')
                ->get(['id', 'name', 'category', 'price', 'stock']),
        ]);
    }
}

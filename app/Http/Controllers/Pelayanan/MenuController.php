<?php

namespace App\Http\Controllers\Pelayanan;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Menampilkan katalog daftar menu lengkap khusus staf pelayanan (pramusaji/waiter)
     * dalam mode pure read-only (hanya melihat informasi, harga, dan ketersediaan menu).
     */
    public function index(Request $request): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = trim((string) $request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->get('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->orderByDesc('is_active')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        // Ringkasan ketersediaan menu untuk panduan cepat staf pelayanan
        $allProducts = Product::all();
        $summary = [
            'total_menu' => $allProducts->count(),
            'available_menu' => $allProducts->where('is_active', true)->count(),
            'out_of_stock_menu' => $allProducts->where('is_active', false)->count(),
            'total_categories' => $categories->count(),
        ];

        return view('pelayanan.menu.index', compact('products', 'categories', 'summary'));
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggest(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) return response()->json(['results' => []]);

        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();

        $products = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop?->id)
            ->where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'LIKE', "%{$q}%")
                    ->orWhere('description', 'LIKE', "%{$q}%");
            })
            ->take(8)
            ->get(['id', 'name', 'price', 'image', 'stock']);

        $results = $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => number_format($p->price),
            'stock' => $p->stock,
            'image' => $p->image ? \Storage::url($p->image) : null,
            'url' => '/product/' . $p->id,
        ]);

        return response()->json(['results' => $results, 'count' => $results->count()]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    /**
     * صفحة الماسح
     */
    public function scanner()
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        return view('dashboard.scanner.index', compact('shop'));
    }

    /**
     * بحث بالباركوود أو SKU
     */
    public function lookup(Request $request)
    {
        $code = trim((string) $request->get('code'));
        if (!$code) {
            return response()->json(['ok' => false, 'message' => 'أدخل كود'], 400);
        }

        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) {
            return response()->json(['ok' => false, 'message' => 'لا يوجد متجر'], 400);
        }

        $variant = ProductVariant::query()
            ->where(function ($q) use ($code) {
                $q->where('barcode', $code)
                  ->orWhere('sku', $code);
            })
            ->with('product')
            ->first();

        if (!$variant) {
            return response()->json(['ok' => false, 'message' => 'لم يُعثر على المنتج'], 404);
        }

        if ($variant->product && $variant->product->shop_id != $shop->id) {
            return response()->json(['ok' => false, 'message' => 'المنتج في متجر آخر'], 403);
        }

        return response()->json([
            'ok' => true,
            'variant' => [
                'id' => $variant->id,
                'size' => $variant->size,
                'color' => $variant->color,
                'color_hex' => $variant->color_hex,
                'stock' => (int) $variant->stock,
                'price' => $variant->price,
                'sku' => $variant->sku,
                'barcode' => $variant->barcode,
                'product' => [
                    'id' => $variant->product->id,
                    'name' => $variant->product->name,
                    'price' => (float) $variant->product->price,
                    'image' => $variant->product->image,
                    'url' => '/dashboard/products/' . $variant->product->id . '/edit',
                ],
            ],
        ]);
    }

    /**
     * تحديث سريع للمخزون
     */
    public function quickUpdate(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|integer',
            'stock' => 'required|integer|min:0',
        ]);

        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) {
            return response()->json(['ok' => false, 'message' => 'لا يوجد متجر'], 400);
        }

        $variant = ProductVariant::with('product')->find($request->variant_id);

        if (!$variant || !$variant->product || $variant->product->shop_id != $shop->id) {
            return response()->json(['ok' => false, 'message' => 'المنتج غير موجود'], 404);
        }

        $variant->update(['stock' => (int) $request->stock]);
        $total = $variant->product->variants()->sum('stock');
        $variant->product->update(['stock' => $total]);

        return response()->json([
            'ok' => true,
            'message' => 'تم تحديث الكمية',
            'new_stock' => (int) $variant->stock,
            'total' => (int) $total,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    /**
     * 📊 قائمة كل المتغيرات
     */
    public function index(Request $request)
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) return redirect('/dashboard')->with('error', 'لا يوجد متجر نشط');

        $productIds = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->pluck('id');

        $query = ProductVariant::whereIn('product_id', $productIds)->with('product');

        // 🔍 فلاتر
        if ($s = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($s) {
                $q->where('sku', 'like', "%$s%")
                  ->orWhere('barcode', 'like', "%$s%")
                  ->orWhere('color', 'like', "%$s%")
                  ->orWhere('size', 'like', "%$s%")
                  ->orWhereHas('product', function ($p) use ($s) {
                      $p->where('name', 'like', "%$s%");
                  });
            });
        }
        if ($pid = $request->input('product_id')) {
            $query->where('product_id', (int) $pid);
        }
        if ($c = $request->input('color')) {
            $query->where('color', $c);
        }
        if ($sz = $request->input('size')) {
            $query->where('size', $sz);
        }
        if ($request->input('low_stock')) {
            $query->where('stock', '<=', 3);
        }
        if ($request->input('inactive')) {
            $query->where('is_active', false);
        }
        if ($request->input('no_stock')) {
            $query->where('stock', 0);
        }

        $variants = $query->orderBy('product_id', 'desc')->orderBy('id')->paginate(30)->withQueryString();

        // 📈 إحصائيات
        $base = ProductVariant::whereIn('product_id', $productIds);
        $stats = [
            'total' => (clone $base)->count(),
            'stock_sum' => (int) (clone $base)->sum('stock'),
            'products_count' => (clone $base)->distinct('product_id')->count('product_id'),
            'low_stock' => (clone $base)->where('stock', '<=', 3)->count(),
            'out_of_stock' => (clone $base)->where('stock', 0)->count(),
        ];

        // للفلاتر
        $products = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->whereHas('variants')
            ->orderBy('name')
            ->get(['id', 'name']);

        $colorsList = ProductVariant::whereIn('product_id', $productIds)
            ->whereNotNull('color')->where('color', '!=', '')
            ->distinct()->orderBy('color')->pluck('color')->values();

        $sizesList = ProductVariant::whereIn('product_id', $productIds)
            ->whereNotNull('size')->where('size', '!=', '')
            ->distinct()->orderBy('size')->pluck('size')->values();

        // 📊 بيانات إضافية للمودالات
        $productsList = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->whereHas('variants')
            ->with(['variants' => function ($q) {
                $q->select('id', 'product_id', 'color', 'size', 'stock');
            }])
            ->get()
            ->map(function ($p) {
                $colors = $p->variants->pluck('color')->filter()->unique()->count();
                $sizes = $p->variants->pluck('size')->filter()->unique()->count();
                $img = $p->image;
                $isExt = $img && (str_starts_with($img, 'http') || str_starts_with($img, 'https'));
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'image' => $img ? ($isExt ? $img : \Storage::url($img)) : null,
                    'variants_count' => $p->variants->count(),
                    'colors' => $colors,
                    'sizes' => $sizes,
                    'stock_total' => (int) $p->variants->sum('stock'),
                ];
            })
            ->sortByDesc('stock_total')
            ->values();

        $lowStockList = ProductVariant::whereIn('product_id', $productIds)
            ->where('stock', '<=', 3)
            ->with('product:id,name')
            ->orderBy('stock')
            ->get()
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'product_id' => $v->product_id,
                    'product_name' => optional($v->product)->name ?? 'منتج محذوف',
                    'color' => $v->color,
                    'size' => $v->size,
                    'stock' => (int) $v->stock,
                ];
            });

        $statsModalData = [
            'products_list' => $productsList,
            'low_stock_list' => $lowStockList,
        ];

        return view('dashboard.variants.index', compact(
            'variants', 'stats', 'products', 'colorsList', 'sizesList', 'shop', 'statsModalData'
        ));
    }

    /**
     * ✏️ تعديل سريع
     */
    public function update(Request $request, ProductVariant $variant)
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) abort(403);

        $product = Product::withoutGlobalScope('tenant')->find($variant->product_id);
        if (!$product || $product->shop_id !== $shop->id) abort(403);

        $data = $request->validate([
            'stock' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (array_key_exists('is_active', $data)) {
            $data['is_active'] = (bool) $data['is_active'];
        }

        $variant->update($data);

        // تحديث مخزون المنتج الإجمالي
        $total = (int) ProductVariant::where('product_id', $variant->product_id)->sum('stock');
        $product->update(['stock' => $total]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'variant' => $variant->fresh(),
                'product_stock' => $total,
            ]);
        }

        return back()->with('success', 'تم تحديث المتغير بنجاح');
    }

    /**
     * 🗑️ حذف متغير
     */
    public function destroy(ProductVariant $variant)
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) abort(403);

        $product = Product::withoutGlobalScope('tenant')->find($variant->product_id);
        if (!$product || $product->shop_id !== $shop->id) abort(403);

        $pid = $variant->product_id;
        $variant->delete();

        // تحديث مخزون المنتج
        $total = (int) ProductVariant::where('product_id', $pid)->sum('stock');
        $product->update(['stock' => $total]);

        return back()->with('success', 'تم حذف المتغير');
    }

    /**
     * 🎯 إجراءات جماعية
     */
    public function bulk(Request $request)
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) abort(403);

        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        $productIds = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)->pluck('id');

        $variants = ProductVariant::whereIn('id', $data['ids'])
            ->whereIn('product_id', $productIds)
            ->get();

        $affectedProducts = [];

        foreach ($variants as $v) {
            $affectedProducts[] = $v->product_id;

            if ($data['action'] === 'delete') {
                $v->delete();
            } else {
                $v->update(['is_active' => $data['action'] === 'activate']);
            }
        }

        // تحديث مخزون المنتجات المتأثرة
        foreach (array_unique($affectedProducts) as $pid) {
            $total = (int) ProductVariant::where('product_id', $pid)->sum('stock');
            Product::withoutGlobalScope('tenant')->where('id', $pid)->update(['stock' => $total]);
        }

        $labels = ['activate' => 'تفعيل', 'deactivate' => 'تعطيل', 'delete' => 'حذف'];
        return back()->with('success', 'تم ' . $labels[$data['action']] . ' ' . count($variants) . ' متغير بنجاح');
    }

    /**
     * 📥 تصدير CSV
     */
    public function export(Request $request)
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) abort(403);

        $productIds = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)->pluck('id');

        $variants = ProductVariant::whereIn('product_id', $productIds)
            ->with('product')->get();

        $filename = 'variants-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($variants) {
            $f = fopen('php://output', 'w');
            // BOM لإكسل العربي
            fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($f, ['المنتج', 'اللون', 'HEX', 'المقاس', 'SKU', 'الباركوود', 'المخزون', 'السعر', 'الحالة']);
            foreach ($variants as $v) {
                fputcsv($f, [
                    optional($v->product)->name ?? '',
                    $v->color ?? '',
                    $v->color_hex ?? '',
                    $v->size ?? '',
                    $v->sku ?? '',
                    $v->barcode ?? '',
                    $v->stock,
                    $v->price ?? '',
                    $v->is_active ? 'مفعّل' : 'معطّل',
                ]);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }
}

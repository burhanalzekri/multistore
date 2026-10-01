<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VariantAnalyticsController extends Controller
{
    // ═══════════════════════════════════════════════════════════
    // 📊 صفحة موحدة — كل المنتجات + منتج محدد
    // ═══════════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $selectedProductId = $request->input('product');

        $productsWithVariants = Product::withoutGlobalScope('tenant')
            ->whereHas('variants')
            ->orderBy('name')
            ->get(['id', 'name']);

        $selectedProduct = null;
        if ($selectedProductId) {
            $selectedProduct = Product::withoutGlobalScope('tenant')
                ->find($selectedProductId);
        }

        if ($selectedProduct) {
            $data = $this->getProductData($selectedProduct);
        } else {
            $data = $this->getOverallData();
        }

        return view('dashboard.analytics.variants-unified', array_merge(
            $data,
            [
                'productsWithVariants' => $productsWithVariants,
                'selectedProduct' => $selectedProduct,
                'selectedProductId' => $selectedProductId,
            ]
        ));
    }

    // ═══════════════════════════════════════════════════════════
    // 📊 بيانات كل المنتجات
    // ═══════════════════════════════════════════════════════════
    private function getOverallData(): array
    {
        $variantsData = OrderItem::whereNotNull('variant_id')
            ->select(
                'variant_id', 'product_id', 'product_name', 'color', 'size',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(unit_price * quantity) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_id) as orders_count')
            )
            ->groupBy('variant_id', 'product_id', 'product_name', 'color', 'size')
            ->orderByDesc('total_qty')
            ->get();

        // معلومات إضافية عن Variants
        $variantIds = $variantsData->pluck('variant_id')->filter()->all();
        $meta = ProductVariant::whereIn('id', $variantIds)
            ->get(['id', 'stock', 'price', 'sku', 'is_active', 'product_id'])
            ->keyBy('id');

        // أسعار المنتجات (fallback للسعر)
        $productIds = $variantsData->pluck('product_id')->filter()->unique()->all();
        $productPrices = Product::withoutGlobalScope('tenant')
            ->whereIn('id', $productIds)
            ->pluck('price', 'id');

        // بناء قائمة Variants
        $variantsList = $variantsData->map(function ($v) use ($meta, $productPrices) {
            $m = $meta->get($v->variant_id);

            // السعر: variant.price → product.price → 0
            $price = $m->price ?? $productPrices->get($v->product_id) ?? 0;

            return [
                'id' => $v->variant_id,
                'product_id' => $v->product_id,
                'product_name' => $v->product_name,
                'color' => $v->color,
                'size' => $v->size,
                'sku' => $m->sku ?? '—',
                'stock' => (int) ($m->stock ?? 0),
                'price' => (float) $price,
                'is_active' => (bool) ($m->is_active ?? true),
                'qty_sold' => (int) $v->total_qty,
                'revenue' => (float) $v->total_revenue,
                'orders_count' => (int) $v->orders_count,
            ];
        })->values()->all();

        // التوزيعات
        $byColor = OrderItem::whereNotNull('variant_id')
            ->whereNotNull('color')
            ->select('color',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(unit_price * quantity) as total_revenue'))
            ->groupBy('color')
            ->orderByDesc('total_qty')
            ->get()
            ->map(fn($c) => [
                'color' => $c->color,
                'qty' => (int) $c->total_qty,
                'revenue' => (float) $c->total_revenue,
            ])->all();

        $bySize = OrderItem::whereNotNull('variant_id')
            ->whereNotNull('size')
            ->select('size',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(unit_price * quantity) as total_revenue'))
            ->groupBy('size')
            ->orderByDesc('total_qty')
            ->get()
            ->map(fn($s) => [
                'size' => $s->size,
                'qty' => (int) $s->total_qty,
                'revenue' => (float) $s->total_revenue,
            ])->all();

        // Stagnant
        $soldIds = OrderItem::whereNotNull('variant_id')
            ->distinct()->pluck('variant_id')->toArray();
        $stagnant = ProductVariant::where('stock', '>', 0)
            ->whereNotIn('id', $soldIds)
            ->with('product:id,name')
            ->limit(20)
            ->get()
            ->map(fn($s) => [
                'product_name' => $s->product?->name ?? '—',
                'color' => $s->color,
                'size' => $s->size,
                'stock' => (int) $s->stock,
            ])->all();

        $totalVariants = ProductVariant::count();

        return [
            'stats' => [
                'total_variants' => $totalVariants,
                'total_stock' => ProductVariant::sum('stock'),
                'total_sold' => array_sum(array_column($variantsList, 'qty_sold')),
                'total_revenue' => array_sum(array_column($variantsList, 'revenue')),
                'stagnant_count' => count($stagnant),
            ],
            'variantsList' => $variantsList,
            'byColor' => $byColor,
            'bySize' => $bySize,
            'stagnant' => $stagnant,
        ];
    }

    // ═══════════════════════════════════════════════════════════
    // 📊 بيانات منتج واحد
    // ═══════════════════════════════════════════════════════════
    private function getProductData(Product $product): array
    {
        $variants = ProductVariant::where('product_id', $product->id)
            ->orderBy('color')->orderBy('size')->get();

        $salesByVariant = OrderItem::where('product_id', $product->id)
            ->whereNotNull('variant_id')
            ->select(
                'variant_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(unit_price * quantity) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_id) as orders_count')
            )
            ->groupBy('variant_id')
            ->get()
            ->keyBy('variant_id');

        $variantsList = $variants->map(function ($v) use ($salesByVariant, $product) {
            $sales = $salesByVariant->get($v->id);
            return [
                'id' => $v->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'color' => $v->color,
                'size' => $v->size,
                'sku' => $v->sku ?? '—',
                'stock' => (int) $v->stock,
                'price' => (float) ($v->price ?? $product->price),
                'is_active' => (bool) $v->is_active,
                'qty_sold' => (int) ($sales->total_qty ?? 0),
                'revenue' => (float) ($sales->total_revenue ?? 0),
                'orders_count' => (int) ($sales->orders_count ?? 0),
            ];
        })->values()->all();

        $colorMap = [];
        $sizeMap = [];
        foreach ($variantsList as $v) {
            if ($v['color']) {
                if (!isset($colorMap[$v['color']])) {
                    $colorMap[$v['color']] = ['color' => $v['color'], 'qty' => 0, 'revenue' => 0];
                }
                $colorMap[$v['color']]['qty'] += $v['qty_sold'];
                $colorMap[$v['color']]['revenue'] += $v['revenue'];
            }
            if ($v['size']) {
                if (!isset($sizeMap[$v['size']])) {
                    $sizeMap[$v['size']] = ['size' => $v['size'], 'qty' => 0, 'revenue' => 0];
                }
                $sizeMap[$v['size']]['qty'] += $v['qty_sold'];
                $sizeMap[$v['size']]['revenue'] += $v['revenue'];
            }
        }
        $byColor = array_values($colorMap);
        $bySize = array_values($sizeMap);
        usort($byColor, fn($a, $b) => $b['qty'] <=> $a['qty']);
        usort($bySize, fn($a, $b) => $b['qty'] <=> $a['qty']);

        $stagnant = array_values(array_filter($variantsList, fn($v) => $v['qty_sold'] == 0 && $v['stock'] > 0));

        return [
            'stats' => [
                'total_variants' => count($variantsList),
                'total_stock' => array_sum(array_column($variantsList, 'stock')),
                'total_sold' => array_sum(array_column($variantsList, 'qty_sold')),
                'total_revenue' => array_sum(array_column($variantsList, 'revenue')),
                'stagnant_count' => count($stagnant),
            ],
            'variantsList' => $variantsList,
            'byColor' => $byColor,
            'bySize' => $bySize,
            'stagnant' => array_map(fn($v) => [
                'product_name' => $product->name,
                'color' => $v['color'],
                'size' => $v['size'],
                'stock' => $v['stock'],
            ], $stagnant),
        ];
    }

    // ═══════════════════════════════════════════════════════════
    // 📥 التصدير (CSV المباشر)
    // ═══════════════════════════════════════════════════════════
    public function exportDetails(Request $request)
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        $productId = $request->input('product');
        $service = app(\App\Services\Analytics\VariantExportService::class);

        if ($productId) {
            return $service->exportProduct((int) $productId, $shop?->name ?? '—');
        }
        return $service->exportDetails($shop?->name ?? '—');
    }

    public function exportByColor()
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        return app(\App\Services\Analytics\VariantExportService::class)
            ->exportByColor($shop?->name ?? '—');
    }

    public function exportBySize()
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        return app(\App\Services\Analytics\VariantExportService::class)
            ->exportBySize($shop?->name ?? '—');
    }
}

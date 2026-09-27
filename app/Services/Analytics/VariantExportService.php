<?php

namespace App\Services\Analytics;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VariantExportService
{
    /**
     * تصدير تفاصيل Variants مع BOM لدعم اللغة العربية في Excel
     */
    public function exportDetails(string $storeName = '—'): StreamedResponse
    {
        $fileName = 'variant_analytics_' . date('Y-m-d_H-i') . '.csv';

        $variantsData = OrderItem::whereNotNull('variant_id')
            ->select(
                'variant_id', 'product_name', 'color', 'size',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(unit_price * quantity) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_id) as orders_count')
            )
            ->groupBy('variant_id', 'product_name', 'color', 'size')
            ->orderByDesc('total_qty')
            ->get();

        $variantIds = $variantsData->pluck('variant_id')->filter()->all();
        $meta = ProductVariant::whereIn('id', $variantIds)
            ->get(['id', 'stock', 'price', 'sku', 'is_active'])
            ->keyBy('id');

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        return response()->stream(function () use ($variantsData, $meta, $storeName) {
            $handle = fopen('php://output', 'w');
            
            // إضافة UTF-8 BOM لفتح الملف بترميز صحيح وعرض اللغة العربية بوضوح في Excel
            fputs($handle, "\xEF\xBB\xBF");

            // ترويسة التقرير
            fputcsv($handle, ['تقرير تحليلات المتغيرات (Variants) - ' . $storeName]);
            fputcsv($handle, ['تاريخ التصدير:', date('Y-m-d H:i')]);
            fputcsv($handle, []); // سطر فارغ

            // أعمدة الجدول
            fputcsv($handle, ['#', 'اسم المنتج', 'اللون', 'الحجم', 'رمز SKU', 'المخزون', 'عدد الطلبات', 'الكمية المباعة', 'سعر الوحدة (ر.ي)', 'إجمالي الإيرادات (ر.ي)', 'الحالة']);

            foreach ($variantsData as $index => $v) {
                $m = $meta->get($v->variant_id);
                fputcsv($handle, [
                    $index + 1,
                    $v->product_name ?? 'غير محدد',
                    $v->color ?? '—',
                    $v->size ?? '—',
                    $m->sku ?? '—',
                    (int) ($m->stock ?? 0),
                    (int) $v->orders_count,
                    (int) $v->total_qty,
                    number_format((float) ($m->price ?? 0), 2, '.', ''),
                    number_format((float) $v->total_revenue, 2, '.', ''),
                    ($m->is_active ?? true) ? 'نشط' : 'معطل',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function exportProduct(int $productId, string $storeName = '—'): StreamedResponse
    {
        $product = Product::withoutGlobalScope('tenant')->find($productId);
        $fileName = 'variant_product_' . $productId . '_' . date('Y-m-d') . '.csv';

        $variants = ProductVariant::where('product_id', $productId)->get();
        $sales = OrderItem::where('product_id', $productId)
            ->whereNotNull('variant_id')
            ->select('variant_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(unit_price * quantity) as total_revenue'), DB::raw('COUNT(DISTINCT order_id) as orders_count'))
            ->groupBy('variant_id')
            ->get()->keyBy('variant_id');

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        return response()->stream(function () use ($product, $variants, $sales, $storeName) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['تقرير متغيرات المنتج: ' . ($product->name ?? '') . ' - ' . $storeName]);
            fputcsv($handle, ['تاريخ التصدير:', date('Y-m-d H:i')]);
            fputcsv($handle, []);

            fputcsv($handle, ['#', 'اللون', 'الحجم', 'SKU', 'المخزون', 'عدد الطلبات', 'الكمية المباعة', 'السعر (ر.ي)', 'الإيرادات (ر.ي)', 'الحالة']);

            foreach ($variants as $i => $v) {
                $s = $sales->get($v->id);
                fputcsv($handle, [
                    $i + 1,
                    $v->color ?? '—',
                    $v->size ?? '—',
                    $v->sku ?? '—',
                    (int) $v->stock,
                    (int) ($s->orders_count ?? 0),
                    (int) ($s->total_qty ?? 0),
                    number_format((float) ($v->price ?? $product->price), 2, '.', ''),
                    number_format((float) ($s->total_revenue ?? 0), 2, '.', ''),
                    $v->is_active ? 'نشط' : 'معطل',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function exportByColor(string $storeName = '—'): StreamedResponse
    {
        $fileName = 'variants_by_color_' . date('Y-m-d') . '.csv';
        $data = OrderItem::whereNotNull('variant_id')->whereNotNull('color')
            ->select('color', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(unit_price * quantity) as total_revenue'))
            ->groupBy('color')->orderByDesc('total_qty')->get();

        $headers = ['Content-Type' => 'text/csv; charset=UTF-8', 'Content-Disposition' => "attachment; filename=\"{$fileName}\""];

        return response()->stream(function () use ($data, $storeName) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['تقرير المبيعات حسب اللون - ' . $storeName]);
            fputcsv($handle, ['#', 'اللون', 'الكمية المباعة', 'الإيرادات (ر.ي)']);
            foreach ($data as $i => $row) {
                fputcsv($handle, [$i + 1, $row->color, (int)$row->total_qty, number_format((float)$row->total_revenue, 2, '.', '')]);
            }
            fclose($handle);
        }, 200, $headers);
    }

    public function exportBySize(string $storeName = '—'): StreamedResponse
    {
        $fileName = 'variants_by_size_' . date('Y-m-d') . '.csv';
        $data = OrderItem::whereNotNull('variant_id')->whereNotNull('size')
            ->select('size', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(unit_price * quantity) as total_revenue'))
            ->groupBy('size')->orderByDesc('total_qty')->get();

        $headers = ['Content-Type' => 'text/csv; charset=UTF-8', 'Content-Disposition' => "attachment; filename=\"{$fileName}\""];

        return response()->stream(function () use ($data, $storeName) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['تقرير المبيعات حسب الحجم - ' . $storeName]);
            fputcsv($handle, ['#', 'الحجم', 'الكمية المباعة', 'الإيرادات (ر.ي)']);
            foreach ($data as $i => $row) {
                fputcsv($handle, [$i + 1, $row->size, (int)$row->total_qty, number_format((float)$row->total_revenue, 2, '.', '')]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}

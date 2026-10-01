<?php

namespace App\Http\Controllers;

use App\Services\Reports\SalesReportService;
use App\Services\Tenant\TenantManager;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * ═══════════════════════════════════════════════════════════
     * 📊 تقرير المبيعات الرئيسي
     * ═══════════════════════════════════════════════════════════
     */
    public function sales(Request $request)
    {
        $shop = app(TenantManager::class)->currentOrFallback();

        if (!$shop) {
            abort(404, 'لم يتم العثور على المتجر');
        }

        $period = $request->input('period', '30days');
        $from = $request->input('from');
        $to = $request->input('to');

        // التحقق من صحة الفترة
        $allowedPeriods = ['today', 'yesterday', '7days', '30days', 'month', 'year', 'custom'];
        if (!in_array($period, $allowedPeriods)) {
            $period = '30days';
        }

        $service = app(SalesReportService::class);
        $report = $service->getReport($shop, $period, $from, $to);

        // العملة من إعدادات المتجر
        $currency = $shop->currency ?? 'YER';

        return view('dashboard.reports.sales', array_merge($report, [
            'shop' => $shop,
            'currency' => $currency,
        ]));
    }

    /**
     * ═══════════════════════════════════════════════════════════
     * 📥 تصدير CSV — تقرير المبيعات
     * ═══════════════════════════════════════════════════════════
     */
    public function exportSales(Request $request)
    {
        $shop = app(TenantManager::class)->currentOrFallback();
        if (!$shop) abort(404);

        $period = $request->input('period', '30days');
        $from = $request->input('from');
        $to = $request->input('to');

        $service = app(SalesReportService::class);
        $report = $service->getReport($shop, $period, $from, $to);

        $currency = $shop->currency ?? 'YER';
        $filename = 'sales-report-' . $shop->id . '-' . date('Y-m-d') . '.csv';

        return response()->stream(function () use ($report, $currency, $shop) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ═══ المبيعات اليومية ═══
            fputcsv($file, ['التاريخ', 'عدد الطلبات', 'الإيرادات (' . $currency . ')']);
            foreach ($report['dailyData'] as $d) {
                fputcsv($file, [
                    $d['date'],
                    $d['orders'],
                    number_format($d['revenue'], 2, '.', ''),
                ]);
            }

            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache',
        ]);
    }

    /**
     * ═══════════════════════════════════════════════════════════
     * 📥 تصدير CSV — أفضل المنتجات
     * ═══════════════════════════════════════════════════════════
     */
    public function exportTopProducts(Request $request)
    {
        $shop = app(TenantManager::class)->currentOrFallback();
        if (!$shop) abort(404);

        $period = $request->input('period', '30days');
        $from = $request->input('from');
        $to = $request->input('to');

        $service = app(SalesReportService::class);
        $report = $service->getReport($shop, $period, $from, $to);

        $currency = $shop->currency ?? 'YER';
        $filename = 'top-products-' . $shop->id . '-' . date('Y-m-d') . '.csv';

        return response()->stream(function () use ($report, $currency) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['المنتج', 'عدد الطلبات', 'الكمية المباعة', 'الإيرادات (' . $currency . ')']);
            foreach ($report['topProducts'] as $p) {
                fputcsv($file, [
                    $p->product_name,
                    $p->orders_count,
                    $p->total_qty,
                    number_format($p->total_revenue, 2, '.', ''),
                ]);
            }

            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache',
        ]);
    }
}

<?php

namespace App\Services\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportService
{
    public function getReport(Shop $shop, string $period = '30days', ?string $from = null, ?string $to = null): array
    {
        [$startDate, $endDate] = $this->resolvePeriod($period, $from, $to);
        $prevRange = $this->getPreviousRange($startDate, $endDate);

        $current = $this->getStatsForRange($shop, $startDate, $endDate);
        $previous = $this->getStatsForRange($shop, $prevRange[0], $prevRange[1]);

        $comparison = [
            'revenue' => $this->percentChange($current['revenue'], $previous['revenue']),
            'orders' => $this->percentChange($current['orders'], $previous['orders']),
            'avg_order' => $this->percentChange($current['avg_order'], $previous['avg_order']),
            'customers' => $this->percentChange($current['customers'], $previous['customers']),
        ];

        $dailyData = $this->getDailySales($shop, $startDate, $endDate);
        $topDays = collect($dailyData)->sortByDesc('revenue')->take(5)->values()->all();
        $worstDays = collect($dailyData)->sortBy('revenue')->take(5)->values()->all();

        $topProducts = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.shop_id', $shop->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.line_total) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as orders_count')
            )
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $topCustomers = Order::where('shop_id', $shop->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id')
            ->select(
                'user_id',
                'customer_name',
                'customer_phone',
                DB::raw('COUNT(id) as orders_count'),
                DB::raw('SUM(total) as total_spent')
            )
            ->groupBy('user_id', 'customer_name', 'customer_phone')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        $byWeekday = $this->getSalesByWeekday($shop, $startDate, $endDate);

        return [
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
                'previous_start' => $prevRange[0],
                'previous_end' => $prevRange[1],
                'label' => $this->periodLabel($period),
                'key' => $period,
            ],
            'current' => $current,
            'previous' => $previous,
            'comparison' => $comparison,
            'dailyData' => $dailyData,
            'topDays' => $topDays,
            'worstDays' => $worstDays,
            'topProducts' => $topProducts,
            'topCustomers' => $topCustomers,
            'byWeekday' => $byWeekday,
        ];
    }

    public function resolvePeriod(string $period, ?string $from, ?string $to): array
    {
        $now = Carbon::now();
        return match ($period) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'yesterday' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
            '7days' => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
            '30days' => [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()],
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'custom' => [
                $from ? Carbon::parse($from)->startOfDay() : $now->copy()->subDays(29)->startOfDay(),
                $to ? Carbon::parse($to)->endOfDay() : $now->copy()->endOfDay(),
            ],
            default => [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()],
        };
    }

    private function getStatsForRange(Shop $shop, Carbon $start, Carbon $end): array
    {
        $orders = Order::where('shop_id', $shop->id)->whereBetween('created_at', [$start, $end])->get();
        $count = $orders->count();
        $revenue = (float) $orders->sum('total');
        $customers = $orders->whereNotNull('user_id')->unique('user_id')->count();

        $itemsSold = (int) OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.shop_id', $shop->id)
            ->whereBetween('orders.created_at', [$start, $end])
            ->sum('order_items.quantity');

        return [
            'revenue' => $revenue,
            'orders' => $count,
            'customers' => $customers,
            'avg_order' => $count > 0 ? $revenue / $count : 0,
            'items_sold' => $itemsSold,
        ];
    }

    private function getPreviousRange(Carbon $start, Carbon $end): array
    {
        $days = $start->diffInDays($end) + 1;
        return [$start->copy()->subDays($days), $end->copy()->subDays($days)];
    }

    private function getDailySales(Shop $shop, Carbon $start, Carbon $end): array
    {
        $data = Order::where('shop_id', $shop->id)
            ->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(id) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $result = [];
        $current = $start->copy();
        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');
            $row = $data->get($dateStr);
            $result[] = [
                'date' => $dateStr,
                'label' => $current->format('m/d'),
                'orders' => (int) ($row->orders ?? 0),
                'revenue' => (float) ($row->revenue ?? 0),
            ];
            $current->addDay();
        }
        return $result;
    }

    private function getSalesByWeekday(Shop $shop, Carbon $start, Carbon $end): array
    {
        $days = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        $result = array_fill_keys(range(0, 6), ['orders' => 0, 'revenue' => 0]);

        $orders = Order::where('shop_id', $shop->id)->whereBetween('created_at', [$start, $end])->get();
        foreach ($orders as $o) {
            $d = (int) $o->created_at->dayOfWeek;
            $result[$d]['orders']++;
            $result[$d]['revenue'] += (float) $o->total;
        }

        return array_map(
            fn($d) => ['day' => $days[$d], 'orders' => $result[$d]['orders'], 'revenue' => $result[$d]['revenue']],
            array_keys($result)
        );
    }

    private function percentChange(float $current, float $previous): array
    {
        if ($previous == 0) {
            return ['value' => $current > 0 ? 100 : 0, 'direction' => $current > 0 ? 'up' : 'neutral'];
        }
        $change = (($current - $previous) / $previous) * 100;
        return [
            'value' => round($change, 1),
            'direction' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral'),
        ];
    }

    private function periodLabel(string $period): string
    {
        return match ($period) {
            'today' => 'اليوم',
            'yesterday' => 'أمس',
            '7days' => 'آخر 7 أيام',
            '30days' => 'آخر 30 يوم',
            'month' => 'هذا الشهر',
            'year' => 'هذه السنة',
            'custom' => 'فترة مخصصة',
            default => 'آخر 30 يوم',
        };
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SmsInbox;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SmartDashboardController extends Controller
{
    public function index()
    {
        // ⭐ العزل — TenantManager هو المصدر الأساسي للمتجر الحالي
        $currentShop = app(\App\Services\Tenant\TenantManager::class)->get();

        if (!$currentShop) {
            abort(404, "لا يوجد متجر محدد لهذا الطلب");
        }

        $shopId = (int) $currentShop->id;

        $stats = [
            "sales" => Order::where("shop_id", $shopId)->where("payment_status", "confirmed")->sum("total"),
            "orders" => Order::where("shop_id", $shopId)->count(),
            "pending" => Order::where("shop_id", $shopId)->where("status", "awaiting_payment")->count(),
            "sms" => (function() use ($shopId) { try { return SmsInbox::where("shop_id", $shopId)->count(); } catch (\Throwable $e) { \Log::warning("SmsInbox stats failed: " . $e->getMessage()); return 0; } })(),
        ];

        $today = Carbon::today();
        $todayStats = [
            "sales" => Order::where("shop_id", $shopId)->where("payment_status", "confirmed")->whereDate("created_at", $today)->sum("total"),
            "orders" => Order::where("shop_id", $shopId)->whereDate("created_at", $today)->count(),
        ];

        $bestSellers = DB::table("order_items")
            ->join("orders", "order_items.order_id", "=", "orders.id")
            ->where("orders.shop_id", $shopId)
            ->where("orders.payment_status", "confirmed")
            ->select("order_items.product_id", "order_items.product_name",
                DB::raw("SUM(order_items.quantity) as total_sold"),
                DB::raw("SUM(order_items.line_total) as total_revenue"))
            ->groupBy("order_items.product_id", "order_items.product_name")
            ->orderBy("total_sold", "desc")
            ->take(5)
            ->get();

        $lowStock = Product::withoutGlobalScope("tenant")
            ->where("shop_id", $shopId)
            ->where("is_active", true)
            ->where("stock", "<=", 10)
            ->where("stock", ">", 0)
            ->orderBy("stock", "asc")
            ->take(5)
            ->get();

        $outOfStock = Product::withoutGlobalScope("tenant")
            ->where("shop_id", $shopId)
            ->where("is_active", true)
            ->where("stock", 0)
            ->orderBy("sold_count", "desc")
            ->take(5)
            ->get();

        $repeatProducts = DB::table("order_items")
            ->join("orders", "order_items.order_id", "=", "orders.id")
            ->where("orders.shop_id", $shopId)
            ->where("orders.payment_status", "confirmed")
            ->where("orders.created_at", ">=", now()->subDays(30))
            ->select("order_items.product_name",
                DB::raw("COUNT(DISTINCT orders.customer_phone) as customer_count"),
                DB::raw("COUNT(*) as order_count"))
            ->groupBy("order_items.product_name")
            ->havingRaw("COUNT(DISTINCT orders.customer_phone) > 1")
            ->orderBy("customer_count", "desc")
            ->take(5)
            ->get();

        $chart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chart[] = [
                "date" => $date->format("m-d"),
                "day" => $date->translatedFormat("D"),
                "sales" => (float) Order::where("shop_id", $shopId)->where("payment_status", "confirmed")->whereDate("created_at", $date)->sum("total"),
                "orders" => Order::where("shop_id", $shopId)->whereDate("created_at", $date)->count(),
            ];
        }

        $alerts = [];

        if ($outOfStock->count() > 0) {
            $alerts[] = ["type" => "danger", "icon" => "x-circle", "title" => $outOfStock->count() . " منتج نفد", "message" => "تحتاج إعادة تخزين", "link" => "/dashboard/products"];
        }
        if ($lowStock->count() > 0) {
            $alerts[] = ["type" => "warning", "icon" => "alert-triangle", "title" => $lowStock->count() . " منتج مخزونه منخفض", "message" => "راجع المخزون", "link" => "/dashboard/products"];
        }
        if ($stats["pending"] > 0) {
            $alerts[] = ["type" => "info", "icon" => "clock", "title" => $stats["pending"] . " طلب بانتظار الدفع", "message" => "راجعها", "link" => "/dashboard/orders?status=awaiting_payment"];
        }
        $reviewSmsCount = 0; try { $reviewSmsCount = SmsInbox::where("shop_id", $shopId)->where("status", "review")->count(); } catch (\Throwable $e) { \Log::warning("ReviewSmsCount failed: " . $e->getMessage()); }
        if ($reviewSmsCount > 0) {
            $alerts[] = ["type" => "warning", "icon" => "message-square", "title" => $reviewSmsCount . " رسالة SMS تحتاج مراجعة", "message" => "راجعها", "link" => "/dashboard/sms?status=review"];
        }
        if ($todayStats["orders"] === 0) {
            $alerts[] = ["type" => "info", "icon" => "trending-up", "title" => "لا توجد مبيعات اليوم بعد", "message" => "جرّب عروض فلاش", "link" => "/dashboard/flash-sales"];
        }

        $recentOrders = Order::where("shop_id", $shopId)->latest()->take(5)->get();
        $smsList = collect(); try { $smsList = SmsInbox::where("shop_id", $shopId)->latest()->take(5)->get(); } catch (\Throwable $e) { \Log::warning("SmsList failed: " . $e->getMessage()); }

        return view("dashboard.smart", compact(
            "stats", "todayStats", "bestSellers", "lowStock", "outOfStock",
            "repeatProducts", "chart", "alerts", "recentOrders", "smsList"
        ));
    }
}

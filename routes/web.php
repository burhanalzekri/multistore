<?php

use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SmsInboxController;
use App\Http\Controllers\SmsWebhookController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// ═══ Storefront ═══
// 🏠 Landing Page — الصفحة الرئيسية التسويقية
Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('home');
Route::get('/shop', [StorefrontController::class, 'index']);
Route::post('/coupon/validate', [\App\Http\Controllers\CouponController::class, 'validate_coupon'])->name('coupon.validate');
// ═══ 🎁 برنامج الولاء (العميل) ═══
Route::get('/loyalty', [\App\Http\Controllers\LoyaltyController::class, 'index'])->name('loyalty.index');
Route::get('/loyalty/history', [\App\Http\Controllers\LoyaltyController::class, 'history'])->name('loyalty.history');
Route::post('/loyalty/redeem', [\App\Http\Controllers\LoyaltyController::class, 'redeem'])->name('loyalty.redeem');
Route::get('/api/loyalty/balance', [\App\Http\Controllers\LoyaltyController::class, 'balance'])->name('loyalty.balance');

// ═══ 🔔 Push Notifications ═══
Route::get('/api/push/public-key', [\App\Http\Controllers\PushController::class, 'publicKey']);
Route::post('/api/push/subscribe', [\App\Http\Controllers\PushController::class, 'subscribe']);
Route::post('/api/push/unsubscribe', [\App\Http\Controllers\PushController::class, 'unsubscribe']);

// 📱 PWA Manifest (ديناميكي لكل متجر)
Route::get('/manifest.webmanifest', function () {
    $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
    $name = $shop?->name ?? 'MultiStore';
    $color = $shop?->primary_color ?? '#d97706';

    // أفضل 10 منتجات
    $topProducts = \DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->whereBetween('orders.created_at', [$start, $end])
        ->whereNotIn('orders.status', ['cancelled'])
        ->select(
            'order_items.product_name as name',
            \DB::raw('SUM(order_items.quantity) as qty'),
            \DB::raw('SUM(order_items.line_total) as revenue')
        )
        ->groupBy('order_items.product_name')
        ->orderByDesc('revenue')
        ->take(10)
        ->get()
        ->map(fn($p) => ['name' => $p->name, 'qty' => (int) $p->qty, 'revenue' => (float) $p->revenue]);

    // أفضل 10 عملاء
    $topCustomers = $orders->groupBy(fn($o) => $o->customer_phone ?: $o->customer_name)
        ->map(function($g, $key) {
            $first = $g->first();
            return [
                'name' => $first->customer_name,
                'phone' => $first->customer_phone ?? '',
                'orders' => $g->count(),
                'revenue' => (float) $g->sum('total'),
            ];
        })
        ->sortByDesc('revenue')
        ->take(10)
        ->values();

    return response()->json([
        'name' => $name,
        'short_name' => mb_substr($name, 0, 12),
        'description' => 'تسوق منتجات ' . $name,
        'start_url' => '/demo-shop',
        'scope' => '/',
        'display' => 'standalone',
        'background_color' => '#ffffff',
        'theme_color' => $color,
        'orientation' => 'portrait',
        'dir' => 'rtl',
        'lang' => 'ar',
        'icons' => [
            ['src' => '/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any maskable'],
            ['src' => '/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable'],
        ],
        'categories' => ['shopping', 'food'],
        'shortcuts' => [
            ['name' => 'السلة', 'url' => '/cart', 'icons' => [['src' => '/icon-192.png', 'sizes' => '192x192']]],
            ['name' => 'المفضلة', 'url' => '/wishlist', 'icons' => [['src' => '/icon-192.png', 'sizes' => '192x192']]],
            ['name' => 'حسابي', 'url' => '/account/login', 'icons' => [['src' => '/icon-192.png', 'sizes' => '192x192']]],
        ],
    ], 200, ['Content-Type' => 'application/manifest+json']);
})->name('manifest');

Route::view('/offline', 'offline')->name('offline');

Route::get('/showcase', fn () => view('showcase'))->name('showcase');
Route::get('/product/{id}', [StorefrontController::class, 'product']);
Route::post('/product/{id}/review', [ReviewController::class, 'store']);
Route::get('/cart', [StorefrontController::class, 'cart']);
Route::post('/cart/add/{id}', [StorefrontController::class, 'addToCart']);
Route::post('/cart/update/{id}', [StorefrontController::class, 'updateCart']);
Route::post('/cart/remove/{id}', [StorefrontController::class, 'removeFromCart']);
Route::get('/checkout', [StorefrontController::class, 'checkout']);
Route::post('/checkout', [StorefrontController::class, 'placeOrder']);
Route::get('/order-success/{id}', [StorefrontController::class, 'orderSuccess']);

// Wishlist (للمستخدمين غير المسجّلين)
Route::get('/wishlist', [WishlistController::class, 'index']);
Route::post('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
Route::post('/price-alert/{id}', [WishlistController::class, 'alert'])->name('price.alert');
Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle']);

// ═══ Auth العملاء ═══
Route::get('/account/login', [CustomerController::class, 'showLogin'])->name('customer.login');
Route::post('/account/login', [CustomerController::class, 'login']);
Route::get('/account/register', [CustomerController::class, 'showRegister'])->name('customer.register');
Route::post('/account/register', [CustomerController::class, 'register']);
Route::post('/account/logout', [CustomerController::class, 'logout'])->name('customer.logout');

// ═══ منطقة العميل ═══
Route::middleware('auth')->prefix('account')->group(function () {
    Route::get('/', [CustomerController::class, 'dashboard']);
    Route::get('/orders', [CustomerController::class, 'orders']);
    Route::get('/orders/{id}', [CustomerController::class, 'orderDetail']);
    Route::get('/wishlist', [CustomerController::class, 'wishlist']);
    Route::get('/profile', [CustomerController::class, 'profile']);
    Route::post('/profile', [CustomerController::class, 'updateProfile']);
});

// ═══ Auth الإدارة ═══
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// ═══ Dashboard الإدارة ═══
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    // 🔍 ماسح الباركوود
        Route::get('scanner', [\App\Http\Controllers\BarcodeController::class, 'scanner'])->name('barcode.scanner');
        Route::get('api/barcode/lookup', [\App\Http\Controllers\BarcodeController::class, 'lookup'])->name('barcode.lookup');
        Route::post('api/barcode/quick-update', [\App\Http\Controllers\BarcodeController::class, 'quickUpdate'])->name('barcode.quickUpdate');

        Route::resource('products', ProductController::class);

        // 📊 إدارة المتغيرات (Variants)
        Route::get('variants', [\App\Http\Controllers\VariantController::class, 'index'])->name('variants.index');
        Route::get('variants/export', [\App\Http\Controllers\VariantController::class, 'export'])->name('variants.export');
        Route::post('variants/bulk', [\App\Http\Controllers\VariantController::class, 'bulk'])->name('variants.bulk');
        Route::patch('variants/{variant}', [\App\Http\Controllers\VariantController::class, 'update'])->name('variants.update');
        Route::delete('variants/{variant}', [\App\Http\Controllers\VariantController::class, 'destroy'])->name('variants.destroy');
    Route::post('products/{product}/delete-image', [ProductController::class, 'deleteImage']);
    Route::post('/categories/quick', [\App\Http\Controllers\CategoryController::class, 'quickStore'])->name('categories.quick');

        Route::resource('categories', CategoryController::class);
        Route::resource('coupons', CouponController::class);
        // 🔔 الإشعارات
    // 📱 سجل SMS
    Route::get('sms-logs', [\App\Http\Controllers\DashboardSmsLogController::class, 'index'])->name('sms-logs.index');
    Route::post('sms-logs/{id}/retry', [\App\Http\Controllers\DashboardSmsLogController::class, 'retry'])->name('sms-logs.retry');
    Route::delete('sms-logs/{id}', [\App\Http\Controllers\DashboardSmsLogController::class, 'destroy'])->name('sms-logs.destroy');
    Route::delete('sms-logs/clear/failed', [\App\Http\Controllers\DashboardSmsLogController::class, 'clearFailed'])->name('sms-logs.clearFailed');
    Route::get('notifications', [\App\Http\Controllers\DashboardNotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\DashboardNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('notifications/read-all', [\App\Http\Controllers\DashboardNotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::delete('notifications/{id}', [\App\Http\Controllers\DashboardNotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('notifications-clear/read', [\App\Http\Controllers\DashboardNotificationController::class, 'clearAll'])->name('notifications.clearRead');
        Route::get('analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics');
    Route::get('loyalty', [\App\Http\Controllers\LoyaltyController::class, 'dashboard'])->name('dashboard.loyalty');
    Route::get('reviews', [AdminReviewController::class, 'index']);
    Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve']);
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy']);
    Route::get('reports', [ReportController::class, 'index']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::get('sms', [SmsInboxController::class, 'index']);
    Route::get('sms/{sms}', [SmsInboxController::class, 'show']);
    Route::post('sms/{sms}/confirm', [SmsInboxController::class, 'confirm']);
    Route::post('sms/{sms}/reject', [SmsInboxController::class, 'reject']);
    Route::get('payments', [PaymentController::class, 'index']);
    Route::get('settings', [SettingsController::class, 'index']);
    Route::post('settings', [SettingsController::class, 'update']);
});

// ═══ API ═══
Route::get('/api/notifications/check', [NotificationController::class, 'check']);

// ═══ Webhook ═══
Route::post('/webhooks/sms/{token}', [SmsWebhookController::class, 'handle'])
    ->withoutMiddleware([\App\Http\Middleware\ResolveTenant::class]);

// Invoices
Route::get("/dashboard/orders/{id}/invoice/download", [App\Http\Controllers\InvoiceController::class, "download"])->name("orders.invoice.download");

// Test Mail (احذفها لاحقًا)
Route::get('/test-mail', [App\Http\Controllers\TestMailController::class, 'send']);

// Sitemap
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index']);

// Invoices
Route::get("/dashboard/orders/{id}/invoice", [App\Http\Controllers\InvoiceController::class, "show"])->name("orders.invoice.show");

// ═══ ميزات Shine الإضافية ═══
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::resource('flash-sales', App\Http\Controllers\FlashSaleController::class);
});

Route::post('/product/{id}/price-alert', [App\Http\Controllers\PriceAlertController::class, 'store']);

Route::get('/compare', [App\Http\Controllers\CompareController::class, 'index']);
Route::post('/compare/toggle/{id}', [App\Http\Controllers\CompareController::class, 'toggle']);
Route::post('/compare/clear', [App\Http\Controllers\CompareController::class, 'clear']);

Route::middleware('auth')->prefix('account')->group(function () {
    Route::get('/addresses', [App\Http\Controllers\AddressController::class, 'index']);
    Route::post('/addresses', [App\Http\Controllers\AddressController::class, 'store']);
    Route::put('/addresses/{address}', [App\Http\Controllers\AddressController::class, 'update']);
    Route::delete('/addresses/{address}', [App\Http\Controllers\AddressController::class, 'destroy']);
    Route::post('/addresses/{address}/default', [App\Http\Controllers\AddressController::class, 'setDefault']);
});

Route::middleware('auth')->get('/dashboard/smart', [App\Http\Controllers\SmartDashboardController::class, 'index']);

// Dashboard API
Route::middleware('auth')->prefix('api/dashboard')->group(function () {
    Route::get('/stats', [App\Http\Controllers\DashboardApiController::class, 'stats']);
    Route::get('/orders', [App\Http\Controllers\DashboardApiController::class, 'recentOrders']);
});

// Maintenance
Route::middleware('auth')->group(function () {
    Route::get('/api/maintenance/status', [App\Http\Controllers\MaintenanceController::class, 'status']);
    Route::post('/api/maintenance/toggle', [App\Http\Controllers\MaintenanceController::class, 'toggle']);
});

// Super Admin
Route::middleware(['auth'])->prefix('super-admin')->group(function () {
    Route::get('/', [App\Http\Controllers\SuperAdminController::class, 'index']);
});

// ═══ إدارة المتاجر ═══


// ═══ إدارة المتاجر (Super Admin) ═══
Route::middleware("auth")->prefix("super-admin")->group(function () {
    // الأكثر تحديدًا أولاً ⚠️
    Route::get("/shops/create", [App\Http\Controllers\ShopManagementController::class, "create"]);
    Route::post("/shops", [App\Http\Controllers\ShopManagementController::class, "store"]);
    Route::get("/shops", [App\Http\Controllers\ShopManagementController::class, "index"]);

    // الأسطر العامة في النهاية
    Route::get("/shops/{shop}", [App\Http\Controllers\SuperAdminController::class, "show"]);
    Route::post("/shops/{shop}/toggle", [App\Http\Controllers\SuperAdminController::class, "toggleStatus"]);
    Route::delete("/shops/{shop}", [App\Http\Controllers\ShopManagementController::class, "destroy"]);
});



// ═══ Search + Shipping ═══
Route::get("/api/search/suggest", [App\Http\Controllers\SearchController::class, "suggest"]);
Route::post("/api/shipping/calculate", [App\Http\Controllers\ShippingController::class, "calculate"]);


// ═══ Staff + Logs + Tasks ═══
Route::middleware("auth")->prefix("dashboard")->group(function () {
    Route::resource("staff", App\Http\Controllers\StaffController::class);
    Route::get("activity-logs", [App\Http\Controllers\ActivityLogController::class, "index"]);
    Route::get("tasks", [App\Http\Controllers\TaskController::class, "index"]);
    Route::post("tasks", [App\Http\Controllers\TaskController::class, "store"]);
    Route::post("tasks/{task}/toggle", [App\Http\Controllers\TaskController::class, "toggle"]);
    Route::delete("tasks/{task}", [App\Http\Controllers\TaskController::class, "destroy"]);
});


Route::post("/api/track", [App\Http\Controllers\TrackingController::class, "track"]);

Route::middleware("auth")->get("/dashboard/recommendations", [App\Http\Controllers\RecommendationStatsController::class, "index"]);

Route::middleware("auth")->get("/dashboard/recommendations", [App\Http\Controllers\RecommendationStatsController::class, "index"]);

Route::get("/api/smart-notifications", [App\Http\Controllers\SmartNotificationController::class, "check"]);
Route::get("/api/cart-reminder", [App\Http\Controllers\SmartNotificationController::class, "cartReminder"]);

Route::get("/api/welcome-coupon", [App\Http\Controllers\SmartCouponController::class, "welcome"]);
Route::get("/api/nudge-coupon", [App\Http\Controllers\SmartCouponController::class, "nudge"]);


// ═══ Order Tracking ═══
Route::get("/track", [App\Http\Controllers\OrderTrackingController::class, "publicTrackForm"]);
Route::post("/track-order", [App\Http\Controllers\OrderTrackingController::class, "search"])->name("track.order");
Route::get("/track-order/{orderNumber}", [App\Http\Controllers\OrderTrackingController::class, "show"]);
Route::middleware("auth")->post("/dashboard/orders/{order}/status", [App\Http\Controllers\OrderTrackingController::class, "updateStatus"])->name("orders.status.update");


// ═══ Order Tracking ═══
Route::get("/track", [App\Http\Controllers\OrderTrackingController::class, "publicTrackForm"]);
Route::get("/track/{orderNumber}", [App\Http\Controllers\OrderTrackingController::class, "show"]);
Route::middleware(["auth", "permission:orders.edit"])->post("/dashboard/orders/{order}/status", [App\Http\Controllers\OrderTrackingController::class, "updateStatus"]);

Route::middleware("auth")->prefix("dashboard")->group(function () {
    Route::post("/settings/smtp", [App\Http\Controllers\SmtpSettingsController::class, "update"]);
    Route::post("/settings/smtp/test", [App\Http\Controllers\SmtpSettingsController::class, "test"]);
});


// ═══ عرض المتاجر المتاحة ═══
Route::get('/shops', function () {
    $shops = \App\Models\Shop::all();
    $html = '<h1>🏪 المتاجر المتاحة</h1><ul>';
    foreach ($shops as $s) {
        $count = \App\Models\Product::where('shop_id', $s->id)->count();
        $html .= '<li><a href="/switch-shop/' . $s->id . '">' . $s->name . '</a> — ' . $count . ' منتج</li>';
    }
    $html .= '</ul>';
    return $html;
})->name('shops.list');


Route::get('/shops', function () {
    $shops = \App\Models\Shop::all();
    $html = '<!DOCTYPE html><html dir="rtl"><head><meta charset="UTF-8"><title>المتاجر</title>';
    $html .= '<script src="https://cdn.tailwindcss.com"></script>';
    $html .= '<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@700;900&display=swap" rel="stylesheet">';
    $html .= '<style>body{font-family:Cairo,sans-serif;background:#f5f7fa;padding:20px;}</style></head><body>';
    $html .= '<div style="max-width:600px;margin:0 auto;">';
    $html .= '<h1 style="font-size:24px;font-weight:900;margin-bottom:20px;">🏪 اختر متجراً</h1>';
    foreach ($shops as $s) {
        $count = \App\Models\Product::where('shop_id', $s->id)->count();
        $cats = \App\Models\Category::where('shop_id', $s->id)->count();
        $html .= '<a href="/switch-shop/' . $s->id . '" style="display:block;background:white;padding:20px;margin-bottom:12px;border-radius:16px;text-decoration:none;color:inherit;box-shadow:0 2px 12px rgba(0,0,0,0.05);border:2px solid #f1f5f9;transition:all 0.2s;" onmouseover="this.style.transform=\'translateY(-3px)\';this.style.borderColor=\'#f59e0b\'" onmouseout="this.style.transform=\'\';this.style.borderColor=\'#f1f5f9\'">';
        $html .= '<div style="display:flex;justify-content:space-between;align-items:center;">';
        $html .= '<div><div style="font-weight:900;font-size:18px;margin-bottom:4px;">' . $s->name . '</div>';
        $html .= '<div style="font-size:13px;color:#6b7280;">📦 ' . $count . ' منتج · 📁 ' . $cats . ' فئة</div></div>';
        $html .= '<div style="background:#f59e0b;color:white;padding:10px 20px;border-radius:12px;font-weight:900;font-size:14px;">دخول →</div>';
        $html .= '</div></a>';
    }
    $html .= '</div></body></html>';
    return $html;
})->name('shops.list');

// ═══ تبديل المتجر ═══
Route::get('/switch-shop/{id}', [App\Http\Controllers\AuthController::class, 'switchShop'])
    ->middleware('auth')
    ->name('switch.shop');

Route::get('/my-shops', [App\Http\Controllers\AuthController::class, 'listShops'])
    ->middleware('auth')
    ->name('my.shops');

// ═══ 🎯 المتجر التجريبي — إنشاء تلقائي ═══
Route::get('/demo', function () {
    // ابحث عن متجر تجريبي
    $demoShop = \App\Models\Shop::where('slug', 'like', 'demo-shop-%')
        ->orWhere('name', 'like', '%تجريبي%')
        ->orderBy('id', 'desc')
        ->first();

    // ✅ إذا غير موجود — أنشئ متجر تجريبي تلقائياً
    if (!$demoShop) {
        try {
            $demoShop = \App\Models\Shop::create([
                'name' => 'متجر تجريبي',
                'slug' => 'demo-shop-' . \Illuminate\Support\Str::random(6),
                'primary_color' => '#f59e0b',
                'currency' => 'YER',
                'locale' => 'ar',
                'webhook_token' => \Illuminate\Support\Str::random(64),
                'status' => 'active',
                'phone' => '777000000',
                'whatsapp' => '777000000',
                'country' => 'اليمن',
                'description' => 'متجر تجريبي لعرض إمكانيات MultiStore',
            ]);

            // ✅ إضافة تصنيفات تجريبية
            $categories = [
                ['name' => 'إلكترونيات', 'slug' => 'electronics'],
                ['name' => 'ملابس', 'slug' => 'clothing'],
                ['name' => 'عطور', 'slug' => 'perfumes'],
            ];

            foreach ($categories as $cat) {
                \App\Models\Category::create([
                    'shop_id' => $demoShop->id,
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'is_active' => true,
                ]);
            }

            // ✅ إضافة منتجات تجريبية
            $products = [
                ['name' => 'سماعات بلوتوث لاسلكية', 'price' => 8500, 'compare_price' => 12000, 'stock' => 15, 'image' => 'headphones-wireless'],
                ['name' => 'ساعة ذكية للرياضة', 'price' => 15500, 'compare_price' => 20000, 'stock' => 8, 'image' => 'smartwatch-sport'],
                ['name' => 'شاحن سريع 65W', 'price' => 3200, 'compare_price' => 4500, 'stock' => 25, 'image' => 'charger-fast'],
                ['name' => 'قميص رجالي كلاسيكي', 'price' => 4500, 'compare_price' => 6000, 'stock' => 30, 'image' => 'shirt-classic'],
                ['name' => 'باور بانك 20000mAh', 'price' => 6500, 'compare_price' => 8500, 'stock' => 12, 'image' => 'powerbank-battery'],
                ['name' => 'كاميرا ويب HD', 'price' => 7800, 'compare_price' => 0, 'stock' => 5, 'image' => 'webcam-camera'],
                ['name' => 'جاكيت جلد رجالي', 'price' => 18500, 'compare_price' => 25000, 'stock' => 7, 'image' => 'leather-jacket'],
                ['name' => 'تيشيرت قطني', 'price' => 2500, 'compare_price' => 0, 'stock' => 50, 'image' => 'tshirt-cotton'],
            ];

            foreach ($products as $p) {
                // استخدام Picsum للحصول على صور احترافية
                $imageUrl = 'https://picsum.photos/seed/' . urlencode($p['image']) . '/600/600';

                \App\Models\Product::create([
                    'shop_id' => $demoShop->id,
                    'name' => $p['name'],
                    'slug' => \Illuminate\Support\Str::slug($p['name']) . '-' . \Illuminate\Support\Str::random(4),
                    'price' => $p['price'],
                    'compare_price' => $p['compare_price'] > 0 ? $p['compare_price'] : null,
                    'stock' => $p['stock'],
                    'is_active' => true,
                    'description' => 'منتج تجريبي لعرض إمكانيات MultiStore — جودة عالية وسعر مميز',
                    'image' => $imageUrl,
                ]);
            }

            // ✅ إنشاء مستخدم تجريبي مرتبط بالمتجر
            \App\Models\User::create([
                'shop_id' => $demoShop->id,
                'name' => 'مدير المتجر التجريبي',
                'email' => 'demo@multistore.ye',
                'password' => \Hash::make('demo123'),
                'role' => 'shop_admin',
                'phone' => '777000000',
            ]);

            \Log::info('✅ تم إنشاء مستخدم تجريبي: demo@multistore.ye');

            \Log::info('✅ تم إنشاء متجر تجريبي: ' . $demoShop->name);

        } catch (\Throwable $e) {
            \Log::error('❌ فشل إنشاء المتجر التجريبي: ' . $e->getMessage());

            return redirect('/login')->with('error', 'تعذر تحميل المتجر التجريبي حالياً. حاول مرة أخرى.');
        }
    }

    // احفظ الجلسة للوصول المستقل
    session(['demo_shop_id' => $demoShop->id]);

    return redirect('/demo-shop');
});

// ═══ 🎮 دخول تجريبي تلقائي إلى لوحة التحكم ═══
Route::get('/demo-dashboard', function () {
    // ابحث عن المستخدم التجريبي
    $demoUser = \App\Models\User::where('email', 'demo@multistore.ye')->first();

    // إذا لم يوجد ← أنشئه
    if (!$demoUser) {
        $demoShop = \App\Models\Shop::where('slug', 'like', 'demo-shop-%')
            ->orWhere('name', 'like', '%تجريبي%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$demoShop) {
            return redirect('/demo')->with('error', 'المتجر التجريبي غير متاح');
        }

        $demoUser = \App\Models\User::create([
            'shop_id' => $demoShop->id,
            'name' => 'مدير المتجر التجريبي',
            'email' => 'demo@multistore.ye',
            'password' => \Hash::make('demo123'),
            'role' => 'shop_admin',
            'phone' => '777000000',
        ]);
    }

    // دخول تلقائي
    \Auth::login($demoUser, true);
    session(['preferred_shop_id' => $demoUser->shop_id]);
    session(['is_demo_mode' => true]);

    return redirect('/dashboard')->with('status', '🎮 مرحباً بك في وضع المتجر التجريبي');
})->name('demo.dashboard');

Route::get('/demo-shop', function () {
    $demoShopId = session('demo_shop_id');
    if (!$demoShopId) {
        return redirect('/demo');
    }

    $demoShop = \App\Models\Shop::find($demoShopId);
    if (!$demoShop) {
        return redirect('/demo');
    }

    // استخدم TenantManager لعرض المتجر التجريبي
    app(\App\Services\Tenant\TenantManager::class)->set($demoShop);

    $request = request();
    $query = \App\Models\Product::withoutGlobalScope('tenant')
        ->where('shop_id', $demoShop->id)
        ->where('is_active', true);

    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }
    if ($request->filled('q')) {
        $term = trim($request->q);
        $query->where(function ($qq) use ($term) {
            $qq->where('name', 'like', "%{$term}%")
               ->orWhere('description', 'like', "%{$term}%");
        });
    }
    if ($request->filled('min_price')) {
        $query->where('price', '>=', (float) $request->min_price);
    }
    if ($request->filled('max_price')) {
        $query->where('price', '<=', (float) $request->max_price);
    }

    $sort = $request->get('sort', 'latest');
    match ($sort) {
        'price_asc' => $query->orderBy('price', 'asc'),
        'price_desc' => $query->orderBy('price', 'desc'),
        'name' => $query->orderBy('name', 'asc'),
        'best' => $query->orderByDesc('sold_count')->orderByDesc('id'),
        default => $query->latest(),
    };

    $products = $query->get();
    $categories = \App\Models\Category::withoutGlobalScope('tenant')
        ->where('shop_id', $demoShop->id)
        ->where('is_active', true)
        ->get();

    $productsJson = $products->map(fn($p) => [
        'id' => $p->id,
        'name' => $p->name,
        'desc' => $p->description ?? '',
        'stock' => (int) $p->stock,
        'price' => (float) $p->price,
        'image' => $p->image,
        'category_id' => $p->category_id,
    ])->values();

    $wishlist = [];
    $shop = $demoShop;

    return view('storefront.index', compact('shop', 'products', 'productsJson', 'categories', 'wishlist'));
})->name('demo.shop');


// ═══ 🔔 إشعارات المشرف ═══
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/notifications', function () {
        $notifications = \App\Models\AdminNotification::latest()->take(50)->get();
        return view('admin.notifications', compact('notifications'));
    })->name('admin.notifications');

    Route::post('/admin/notifications/{id}/read', function ($id) {
        $n = \App\Models\AdminNotification::findOrFail($id);
        $n->update(['is_read' => true, 'read_at' => now()]);
        return back();
    })->name('admin.notifications.read');

    Route::post('/admin/notifications/read-all', function () {
        \App\Models\AdminNotification::where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        return back();
    })->name('admin.notifications.readAll');
});

// ═══ 👑 إدارة المتاجر — لمالك المشروع فقط ═══
Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/shops', [App\Http\Controllers\OwnerShopsController::class, 'index'])->name('shops.index');
    Route::get('/shops/create', [App\Http\Controllers\OwnerShopsController::class, 'create'])->name('shops.create');
    Route::post('/shops', [App\Http\Controllers\OwnerShopsController::class, 'store'])->name('shops.store');
    Route::get('/shops/{id}', [App\Http\Controllers\OwnerShopsController::class, 'show'])->name('shops.show');
    Route::get('/shops/{id}/edit', [App\Http\Controllers\OwnerShopsController::class, 'edit'])->name('shops.edit');
    Route::put('/shops/{id}', [App\Http\Controllers\OwnerShopsController::class, 'update'])->name('shops.update');
    Route::delete('/shops/{id}', [App\Http\Controllers\OwnerShopsController::class, 'destroy'])->name('shops.destroy');
    Route::post('/shops/{id}/toggle-status', [App\Http\Controllers\OwnerShopsController::class, 'toggleStatus'])->name('shops.toggleStatus');
    Route::post('/shops/{id}/extend-trial', [App\Http\Controllers\OwnerShopsController::class, 'extendTrial'])->name('shops.extendTrial');
});
// Barcode uniqueness check
Route::middleware(['auth'])->get('/dashboard/products/check-barcode', [\App\Http\Controllers\ProductController::class, 'checkBarcode'])->name('products.checkBarcode');

// صفحة طلبات المستخدم
Route::middleware('auth')->get('/orders', function () {
    $orders = \App\Models\Order::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    return view('storefront.orders', compact('orders'));
})->name('orders.index');

// صفحة تواصل معنا
Route::get('/contact', function () {
    $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
    if (!$shop) {
        $shop = \App\Models\Shop::first();
    }
    return view('storefront.contact', compact('shop'));
})->name('contact');
Route::middleware(['auth'])->prefix('dashboard')->post('/loyalty/grant', [\App\Http\Controllers\LoyaltyController::class, 'grantPoints'])->name('loyalty.grant');
Route::middleware(['auth'])->prefix('dashboard')->group(function() {
        // 📧 حملات بريدية
        Route::get('campaigns', [\App\Http\Controllers\CampaignController::class, 'index'])->name('campaigns.index');
        Route::get('campaigns/create', [\App\Http\Controllers\CampaignController::class, 'create'])->name('campaigns.create');
        Route::post('campaigns', [\App\Http\Controllers\CampaignController::class, 'store'])->name('campaigns.store');
        Route::get('campaigns/{campaign}', [\App\Http\Controllers\CampaignController::class, 'show'])->name('campaigns.show');
        Route::post('campaigns/{campaign}/send', [\App\Http\Controllers\CampaignController::class, 'send'])->name('campaigns.send');
        Route::delete('campaigns/{campaign}', [\App\Http\Controllers\CampaignController::class, 'destroy'])->name('campaigns.destroy');

});
Route::get('/api/autocomplete', [\App\Http\Controllers\StorefrontController::class, 'autocomplete'])->name('storefront.autocomplete');


// ═══════════════════════════════════════════════════════════

// 📥 تصدير CSV — Variants Analytics

// ═══════════════════════════════════════════════════════════
// 📊 Variant Analytics — واجهة موحدة (تبويبات + تصدير)
// ═══════════════════════════════════════════════════════════
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/analytics/variants',
        [\App\Http\Controllers\VariantAnalyticsController::class, 'index'])
        ->name('variants.analytics.index');

    Route::get('/analytics/variants/export/details',
        [\App\Http\Controllers\VariantAnalyticsController::class, 'exportDetails'])
        ->name('variants.analytics.export.details');

    Route::get('/analytics/variants/export/by-color',
        [\App\Http\Controllers\VariantAnalyticsController::class, 'exportByColor'])
        ->name('variants.analytics.export.byColor');

    Route::get('/analytics/variants/export/by-size',
        [\App\Http\Controllers\VariantAnalyticsController::class, 'exportBySize'])
        ->name('variants.analytics.export.bySize');

    Route::get('/products/{id}/variants/analytics',
        fn($id) => redirect('/dashboard/analytics/variants?product=' . $id))
        ->name('variants.analytics.product');

    Route::get('/products/{id}/variants/analytics/export',
        [\App\Http\Controllers\VariantAnalyticsController::class, 'exportDetails'])
        ->name('variants.analytics.product.export');
});


// ═══════════════════════════════════════════════════════════
// 📊 تقارير المبيعات المتقدمة
// ═══════════════════════════════════════════════════════════
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/reports/sales',
        [\App\Http\Controllers\ReportsController::class, 'sales'])
        ->name('reports.sales');

    Route::get('/reports/sales/export',
        [\App\Http\Controllers\ReportsController::class, 'exportSales'])
        ->name('reports.sales.export');

    Route::get('/reports/sales/export/top-products',
        [\App\Http\Controllers\ReportsController::class, 'exportTopProducts'])
        ->name('reports.sales.export.products');
});

// ═══ API إحصائيات المتجر ═══
Route::get('/api/shop-stats', function (\Illuminate\Http\Request $req) {
    $shopId = $req->get('shop_id');
    $period = $req->get('period', '30d');
    $from = $req->get('from');
    $to = $req->get('to');

    // تحديد النطاق الزمني
    $now = now();
    switch ($period) {
        case 'today':  $start = $now->copy()->startOfDay();   $end = $now->copy()->endOfDay();   break;
        case '7d':     $start = $now->copy()->subDays(6)->startOfDay(); $end = $now->copy()->endOfDay(); break;
        case '30d':    $start = $now->copy()->subDays(29)->startOfDay(); $end = $now->copy()->endOfDay(); break;
        case 'month':  $start = $now->copy()->startOfMonth(); $end = $now->copy()->endOfMonth(); break;
        case 'year':   $start = $now->copy()->startOfYear();  $end = $now->copy()->endOfYear();  break;
        case 'custom':
            $start = $from ? \Carbon\Carbon::parse($from)->startOfDay() : $now->copy()->subDays(29)->startOfDay();
            $end   = $to   ? \Carbon\Carbon::parse($to)->endOfDay()     : $now->copy()->endOfDay();
            break;
        default:       $start = $now->copy()->subDays(29)->startOfDay(); $end = $now->copy()->endOfDay();
    }

    $q = \App\Models\Order::query()
        ->when($shopId, fn($x) => $x->where('shop_id', $shopId))
        ->whereBetween('created_at', [$start, $end])
        ->whereNotIn('status', ['cancelled']);

    $orders = $q->get();
    $revenue = (float) $orders->sum('total');
    $count   = $orders->count();
    $avg     = $count > 0 ? $revenue / $count : 0;
    $custKey = $orders->map(fn($o) => $o->customer_phone ?: ($o->customer_email ?: $o->customer_name))->filter()->unique()->count();

    // المبيعات اليومية
    $daily = $orders->groupBy(fn($o) => $o->created_at->format('Y-m-d'))
        ->map(fn($g, $d) => ['date' => $d, 'revenue' => (float) $g->sum('total'), 'orders' => $g->count()])
        ->values();

    // الأيام حسب اسم اليوم
    $dowNames = ['الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
    $byDow = [];
    foreach ($dowNames as $n) $byDow[$n] = ['revenue' => 0, 'orders' => 0];
    foreach ($orders as $o) {
        $d = $dowNames[$o->created_at->dayOfWeek];
        $byDow[$d]['revenue'] += (float) $o->total;
        $byDow[$d]['orders']  += 1;
    }

    // أفضل 5 أيام
    $top = $orders->groupBy(fn($o) => $o->created_at->format('Y-m-d'))
        ->map(fn($g, $d) => ['date' => $d, 'orders' => $g->count(), 'revenue' => (float) $g->sum('total')])
        ->sortByDesc('revenue')->take(5)->values();

    return response()->json([
        'ok' => true,
        'period' => ['from' => $start->toDateString(), 'to' => $end->toDateString(), 'label' => $period],
        'kpi' => [
            'revenue'   => round($revenue, 2),
            'orders'    => $count,
            'avg'       => round($avg, 2),
            'customers' => $custKey,
        ],
        'daily'  => $daily,
        'by_dow' => $byDow,
        'top'    => $top,
        'top_products' => $topProducts,
        'top_customers' => $topCustomers,
    ]);
})->middleware('web');

// ═══════════════════════════════════════════════════════════
// 🏠 الصفحة التسويقية
// ═══════════════════════════════════════════════════════════
Route::get('/landing', [\App\Http\Controllers\LandingController::class, 'index'])
    ->name('landing.index');

// ═══════════════════════════════════════════════════════════
// 🚚 مناطق الشحن
// ═══════════════════════════════════════════════════════════
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/shipping-zones', [\App\Http\Controllers\ShippingZoneController::class, 'index'])->name('shipping-zones.index');
    Route::get('/shipping-zones/create', [\App\Http\Controllers\ShippingZoneController::class, 'create'])->name('shipping-zones.create');
    Route::post('/shipping-zones', [\App\Http\Controllers\ShippingZoneController::class, 'store'])->name('shipping-zones.store');
    Route::get('/shipping-zones/{id}/edit', [\App\Http\Controllers\ShippingZoneController::class, 'edit'])->name('shipping-zones.edit');
    Route::put('/shipping-zones/{id}', [\App\Http\Controllers\ShippingZoneController::class, 'update'])->name('shipping-zones.update');
    Route::delete('/shipping-zones/{id}', [\App\Http\Controllers\ShippingZoneController::class, 'destroy'])->name('shipping-zones.destroy');
    Route::post('/shipping-zones/{id}/toggle', [\App\Http\Controllers\ShippingZoneController::class, 'toggle'])->name('shipping-zones.toggle');
});

// ═══ API ═══
Route::get('/api/shipping-zones', [\App\Http\Controllers\ShippingZoneController::class, 'apiList'])->name('api.shipping-zones');
Route::post('/api/shipping-zones/calculate', [\App\Http\Controllers\ShippingZoneController::class, 'apiCalculate'])->name('api.shipping-zones.calculate');


// ═══════════════════════════════════════════════════════════
// 🎨 شعار المتجر
// ═══════════════════════════════════════════════════════════
Route::middleware('auth')->prefix('dashboard/settings')->group(function () {
    Route::post('/upload-logo', [\App\Http\Controllers\SettingsController::class, 'uploadLogo'])->name('settings.upload-logo');
    Route::delete('/delete-logo', [\App\Http\Controllers\SettingsController::class, 'deleteLogo'])->name('settings.delete-logo');
});

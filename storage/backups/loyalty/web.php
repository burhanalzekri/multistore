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
Route::get('/', [StorefrontController::class, 'index']);
Route::get('/shop', [StorefrontController::class, 'index']);
Route::post('/coupon/validate', [\App\Http\Controllers\CouponController::class, 'validate_coupon'])->name('coupon.validate');
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
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/delete-image', [ProductController::class, 'deleteImage']);
    Route::resource('categories', CategoryController::class);
    Route::resource('coupons', CouponController::class);
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
    Route::get('/loyalty', [App\Http\Controllers\LoyaltyController::class, 'index']);
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

Route::get("/api/search/suggest", [App\Http\Controllers\SearchController::class, "suggest"]);


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

// ═══ مسار تبديل المتجر (للتطوير) ═══
Route::get('/switch-shop/{shopId}', function ($shopId) {
    $shop = \App\Models\Shop::findOrFail($shopId);
    session(['preferred_shop_id' => $shop->id]);
    app(\App\Services\Tenant\TenantManager::class)->set($shop);
    return redirect('/shop')->with('success', 'تم التبديل إلى: ' . $shop->name);
})->name('switch.shop');

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

// ═══ تبديل المتجر (للتطوير) ═══
Route::get('/switch-shop/{id}', function ($id) {
    $shop = \App\Models\Shop::findOrFail($id);
    session(['preferred_shop_id' => $shop->id]);
    app(\App\Services\Tenant\TenantManager::class)->set($shop);
    return redirect('/shop')->with('success', 'تم التبديل إلى: ' . $shop->name);
})->name('switch.shop');

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

// ═══ 🎯 المتجر التجريبي ═══
Route::get('/demo', function () {
    $demoShop = \App\Models\Shop::where('slug', 'like', 'demo-shop-%')
        ->orWhere('name', 'like', '%تجريبي%')
        ->orderBy('id', 'desc')
        ->first();

    if (!$demoShop) {
        abort(404, 'المتجر التجريبي غير موجود');
    }

    // احفظ الجلسة للوصول المستقل
    session(['demo_shop_id' => $demoShop->id]);

    return redirect('/demo-shop');
});

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

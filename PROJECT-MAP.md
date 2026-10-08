# 🗺️ MultiStore — خريطة المشروع الكاملة

**تاريخ الإنشاء:** 2026-10-07 17:51

## 🎮 Controllers

| Controller | Methods | الحجم |
|-----------|---------|-------|
| **ActivityLogController** | index | 13 |
| **AddressController** | index,store,update,destroy,setDefault | 65 |
| **AdminReviewController** | index,approve,destroy,toggleVisibility | 43 |
| **AnalyticsController** | index | 100 |
| **AuthController** | showLogin,login,showRegister,register,switchShop,listShops,logout | 210 |
| **BarcodeController** | scanner,lookup,quickUpdate | 104 |
| **CampaignController** | index,create,store,show,send,destroy | 330 |
| **CategoryController** | index,create,store,edit,update,destroy,quickStore | 110 |
| **CompareController** | index,toggle,clear | 32 |
| **ContactMessageController** | store,index,show,destroy | 95 |
| **Controller** |  | 8 |
| **CouponController** | index,create,store,edit,update,destroy,validate_coupon | 227 |
| **CustomerController** | showLogin,login,showRegister,register,logout,dashboard,orders,orderDetail,profile,updateProfile,wishlist | 249 |
| **DashboardApiController** | stats,recentOrders | 37 |
| **DashboardController** | index | 15 |
| **DashboardNotificationController** | index,markRead,markAllRead,destroy,clearAll,count | 76 |
| **DashboardSmsLogController** | index,retry,destroy,clearFailed | 52 |
| **EmailSubscriberController** | subscribe | 52 |
| **FlashSaleController** | index,create,store,destroy | 37 |
| **InvoiceController** | show | 21 |
| **LandingController** | index | 18 |
| **LandingSettingsController** | index,update,toggle,uploadImage | 165 |
| **LoyaltyController** | __construct,index,history,redeem,balance,dashboard,grantPoints | 168 |
| **MaintenanceController** | status,toggle | 37 |
| **NotificationController** | check | 18 |
| **OrderController** | index,show,updateStatus | 63 |
| **OrderTrackingController** | publicTrackForm,search,show,updateStatus | 158 |
| **OwnerShopsController** | index,create,store,show,edit,update,destroy,toggleStatus,extendTrial | 299 |
| **PaymentController** | index | 13 |
| **PlatformReportController** | index | 69 |
| **PriceAlertController** | store | 21 |
| **ProductController** | __construct,index,create,store,show,edit,update,destroy,checkBarcode | 348 |
| **ProfileController** | showPasswordForm,updatePassword | 47 |
| **PushController** | __construct,publicKey,subscribe,unsubscribe,test | 54 |
| **RecommendationStatsController** | index | 64 |
| **ReportController** | index | 51 |
| **ReportsController** | sales,exportSales,exportTopProducts | 129 |
| **ReviewController** | store | 58 |
| **SearchController** | suggest | 38 |
| **SettingsController** | index,update,uploadLogo,deleteLogo | 121 |
| **ShippingController** | calculate | 46 |
| **ShippingZoneController** | index,create,store,edit,update,destroy,toggle,apiList,apiCalculate | 219 |
| **ShopManagementController** | index,create,store,destroy,edit,update | 99 |
| **SitemapController** | index | 42 |
| **SmartCouponController** | welcome,nudge | 149 |
| **SmartDashboardController** | index | 119 |
| **SmartNotificationController** | check,cartReminder | 60 |
| **SmsCenterController** | index | 81 |
| **SmsInboxController** | index,show,confirm,reject | 65 |
| **SmsTemplateController** | index,update,reset,preview | 95 |
| **SmsWebhookController** | handle | 52 |
| **SmtpSettingsController** | update,test | 69 |
| **StaffController** | index,create,store,edit,update,destroy | 107 |
| **StorefrontController** | index,product,cart,addToCart,updateCart,removeFromCart,checkout,placeOrder,orderSuccess,trackOrder,autocomplete | 841 |
| **SubscriptionController** | index,assign,cancel,plans | 58 |
| **SuperAdminController** | index,toggleStatus,show,impersonate,stopImpersonating,users,showUser,createUser,storeUser,showResetPassword,resetPassword | 281 |
| **SystemHealthController** | index | 20 |
| **TaskController** | index,store,toggle,destroy | 56 |
| **TestMailController** | send | 28 |
| **TestimonialController** | store,index,toggle,destroy | 86 |
| **TrackingController** | track | 24 |
| **VariantAnalyticsController** | index,exportDetails,exportByColor,exportBySize | 270 |
| **VariantController** | index,update,destroy,bulk,export | 281 |
| **WishlistController** | index,toggle,clear,alert | 167 |


## 📦 Models

| Model | fillable | Relations | الحجم |
|-------|----------|-----------|-------|
| **ActivityLog** | — | — | 25 |
| **AdminNotification** | — | — | 40 |
| **Category** | — | Product | 13 |
| **ContactMessage** | — | Shop | 24 |
| **Coupon** | — | — | 79 |
| **CustomerAddress** | — | — | 9 |
| **CustomerEvent** | — | — | 9 |
| **CustomerProfile** | — | User | 16 |
| **EmailCampaign** | — | EmailCampaignRecipient,Shop | 85 |
| **EmailCampaignRecipient** | — | EmailCampaign,User | 50 |
| **EmailSubscriber** | — | — | 17 |
| **FlashSale** | — | Product | 24 |
| **LoyaltyPoint** | — | User | 9 |
| **LoyaltyTransaction** | — | — | 8 |
| **Order** | — | OrderItem,OrderStatusHistory,Shop | 29 |
| **OrderItem** | — | Order,Product,ProductVariant | 31 |
| **OrderStatusHistory** | — | Order,User | 12 |
| **PaymentTransaction** | — | Order,Shop,SmsInbox | 16 |
| **PaymentWallet** | — | — | 11 |
| **Plan** | — | Subscription | 11 |
| **PlatformInvoice** | — | Shop,Subscription | 12 |
| **PriceAlert** | — | Product,User | 12 |
| **Product** | — | Category,ProductVariant | 192 |
| **ProductRecommendation** | — | Product | 14 |
| **ProductVariant** | — | Product | 61 |
| **ProductView** | — | Product | 10 |
| **PushSubscription** | — | Shop,User | 22 |
| **Review** | — | Product | 13 |
| **ShippingZone** | — | Shop | 110 |
| **Shop** | — | Category,Order,PaymentWallet,Product,User | 41 |
| **SiteSetting** | group,key,value,type | — | 38 |
| **SmsInbox** | — | Order,PaymentTransaction,Shop | 23 |
| **SmsLog** | — | Shop | 22 |
| **SmsPattern** | — | — | 9 |
| **SmsTemplate** | — | Shop | 25 |
| **Subscription** | — | Plan,Shop | 23 |
| **Task** | — | User | 13 |
| **Testimonial** | — | — | 17 |
| **User** | — | Shop | 17 |
| **Wishlist** | — | Product | 13 |

## ⚙️ Services

| Service | الحجم |
|---------|-------|
| Analytics/VariantExportService.php | 164 |
| CloudinaryService.php | 164 |
| Loyalty/LoyaltyService.php | 86 |
| Mail/TenantMailer.php | 53 |
| Notifications/NotificationService.php | 313 |
| OneSignal/OneSignalService.php | 147 |
| Payment/PaymentMatcher.php | 93 |
| Push/WebPushService.php | 131 |
| Recommendation/BehaviorTracker.php | 102 |
| Recommendation/RecommendationEngine.php | 124 |
| Reports/SalesReportService.php | 203 |
| ShopThemeDetector.php | 224 |
| Sms/SmsParser.php | 36 |
| Sms/SmsSender.php | 180 |
| Sms/SmsTemplateService.php | 101 |
| Telegram/TelegramSender.php | 75 |
| Tenant/TenantManager.php | 46 |

## 🛡️ Middleware

| Middleware | الحجم |
|-----------|-------|
| **CheckMaintenance** | 36 |
| **CheckPermission** | 28 |
| **RequireSuperAdmin** | 28 |
| **ResolveTenant** | 132 |

## 🎯 Concerns / Traits

| Concern | الحجم |
|---------|-------|
| Models/Concerns/BelongsToTenant.php | 76 |

## 🛣️ Routes (201)

### حسب HTTP Method

| Method | العدد |
|--------|-------|
| GET | 0
0 |
| POST | 0
0 |
| PUT | 0
0 |
| PATCH | 0
0 |
| DELETE | 0
0 |

### الـ Route Groups الرئيسية

```
108:Route::middleware('auth')->prefix('account')->group(function () {
125:Route::middleware('auth')->prefix('dashboard')->group(function () {
199:Route::middleware('auth')->get("/dashboard/orders/{id}/invoice/download", [App\Http\Controllers\InvoiceController::class, "download"])->name("orders.invoice.download");
207:Route::middleware('auth')->get("/dashboard/orders/{id}/invoice", [App\Http\Controllers\InvoiceController::class, "show"])->name("orders.invoice.show");
210:Route::middleware('auth')->prefix('dashboard')->group(function () {
220:Route::middleware('auth')->prefix('account')->group(function () {
228:Route::middleware('auth')->get('/dashboard/smart', [App\Http\Controllers\SmartDashboardController::class, 'index']);
231:Route::middleware('auth')->prefix('api/dashboard')->group(function () {
237:Route::middleware('auth')->group(function () {
243:Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->group(function () {
268:Route::middleware(["auth", "super_admin"])->prefix("super-admin")->group(function () {
290:Route::middleware("auth")->prefix("dashboard")->group(function () {
302:Route::middleware("auth")->get("/dashboard/recommendations", [App\Http\Controllers\RecommendationStatsController::class, "index"]);
316:Route::middleware("auth")->post("/dashboard/orders/{order}/status", [App\Http\Controllers\OrderTrackingController::class, "updateStatus"])->name("orders.status.update");
322:Route::middleware(["auth", "permission:orders.edit"])->post("/dashboard/orders/{order}/status", [App\Http\Controllers\OrderTrackingController::class, "updateStatus"]);
324:Route::middleware("auth")->prefix("dashboard")->group(function () {
363:Route::middleware(['auth'])->group(function () {
383:Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
395:Route::middleware(['auth'])->get('/dashboard/products/check-barcode', [\App\Http\Controllers\ProductController::class, 'checkBarcode'])->name('products.checkBarcode');
398:Route::middleware('auth')->get('/orders', function () {
416:Route::middleware(['auth'])->prefix('dashboard')->post('/loyalty/grant', [\App\Http\Controllers\LoyaltyController::class, 'grantPoints'])->name('loyalty.grant');
417:Route::middleware(['auth'])->prefix('dashboard')->group(function() {
437:Route::middleware('auth')->prefix('dashboard')->group(function () {
467:Route::middleware('auth')->prefix('dashboard')->group(function () {
563:Route::middleware('auth')->prefix('dashboard')->group(function () {
581:Route::middleware('auth')->prefix('dashboard/settings')->group(function () {
```

## 🗄️ Migrations (بنية DB)

| Migration |
|-----------|
| 0001_01_01_000000_create_users_table |
| 0001_01_01_000001_create_cache_table |
| 0001_01_01_000002_create_jobs_table |
| 2026_01_01_000100_create_saas_tables |
| 2026_01_02_000001_create_categories_reviews_wishlists |
| 2026_01_03_000001_create_coupons_table |
| 2026_01_04_000001_add_customer_fields |
| 2026_01_05_000001_create_shine_features |
| 2026_01_06_000001_create_billing_tables |
| 2026_01_07_000001_create_staff_tables |
| 2026_01_08_000001_create_recommendation_system |
| 2026_01_09_000001_create_order_tracking |
| 2026_01_11_000001_add_smtp_to_shops |
| 2026_01_12_000001_fix_history_table |
| 2026_09_22_182627_add_customer_rating_to_orders_table |
| 2026_09_23_000001_add_registration_fields_to_shops_table |
| 2026_09_23_100000_create_admin_notifications_table |
| 2026_09_24_142731_create_sms_logs_table |
| 2026_09_24_170407_create_push_subscriptions_table |
| 2026_09_25_145739_add_video_to_products_table |
| 2026_09_25_151538_add_sizes_colors_to_products_table |
| 2026_09_25_152145_create_product_variants_table |
| 2026_09_25_153801_add_color_hex_to_variants |
| 2026_09_25_173623_add_barcode_to_product_variants |
| 2026_09_25_213154_add_barcode_to_products_table |
| 2026_09_26_011738_add_variant_labels_to_products |
| 2026_09_26_023330_add_variant_columns_to_order_items |
| 2026_09_26_165652_add_advanced_fields_to_coupons |
| 2026_09_26_171552_add_points_to_orders |
| 2026_09_26_172949_add_coupon_to_orders |
| 2026_09_26_175520_create_email_campaigns_tables |
| 2026_09_27_200052_fix_sms_inbox_fk |
| 2026_09_27_221741_create_shipping_zones_table |
| 2026_09_27_223152_add_max_order_to_shipping_zones |
| 2026_09_27_223153_add_shipping_status_to_orders |
| 2026_09_28_170123_create_sms_templates_table |
| 2026_09_29_173554_create_contact_messages_table |
| 2026_09_29_174455_create_testimonials_table |
| 2026_09_30_171804_add_performance_indexes |
| 2026_09_30_234643_create_email_subscribers_table |
| 2026_10_01_004155_create_site_settings_table |

## 📄 Views (الهيكل)

| المجلد | عدد الملفات |
|--------|-------------|
| **admin/** | 1 |
| **auth/** | 3 |
| **components/** | 10 |
| **customer/** | 10 |
| **dashboard/** | 57 |
| **emails/** | 1 |
| **errors/** | 8 |
| **invoices/** | 1 |
| **landing/** | 1 |
| **layouts/** | 4 |
| **owner/** | 4 |
| **storefront/** | 21 |
| **super-admin/** | 13 |

**الإجمالي:** 138 ملف blade

---

## 📊 إحصائيات شاملة

| العنصر | العدد |
|--------|-------|
| Controllers | 64 |
| Models | 40 |
| Services | 17 |
| Middleware | 4 |
| Migrations | 41 |
| Routes | 201 |
| Blade files | 138 |

## 🔑 الأوامر المهمة

```bash
# تشغيل السيرفر
php artisan serve --host=0.0.0.0 --port=8001

# مسح الكاش
php artisan optimize:clear

# إعادة symlink
php artisan storage:link

# فحص DB
sqlite3 database/database.sqlite ".tables"
```

## 🔐 بيانات الدخول (محلي)

| الدور | البريد | كلمة المرور |
|-------|--------|-------------|
| 👑 Super Admin | super@admin.com | admin123 |
| 🏪 البائع | seller@demo.com | password123 |

> ⚠️ للإنتاج: استخدم كلمات مرور قوية + Cloudinary Keys جديدة

## 🌐 البيئة

- **الإطار:** Laravel 13 + PHP 8.5
- **DB محلي:** SQLite
- **DB إنتاج:** PostgreSQL (Render)
- **التخزين:** Cloudinary
- **النشر:** Render
- **GitHub:** github.com/burhanalzekri/multistore
- **الفرع النشط:** feature/image-tools

---

*تم إنشاء هذا الملف تلقائياً — 2026-10-07 18:19*

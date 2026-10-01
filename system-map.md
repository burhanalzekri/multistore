============================================================
🗺️  خريطة النظام — MultiStore
📅  2026-09-29 20:58
============================================================

## 1. معلومات عامة

• المسار:        /data/data/com.termux/files/home/multistore
• الفرع:         main
• آخر commit:    4ffb600 chore(routes): remove duplicate routes
• عدد commits:   36
• تعديلات غير مرفوعة: (نظيف)
• PHP:           PHP 8.5.1 (cli) (built: Dec 22 2025 00:03:00) (NTS)
• Laravel:       Laravel Framework 13.32.0


## 2. البنية الأساسية


📁 app/Http/Controllers/   (76 عنصر)
    ActivityLogController.php
    AddressController.php
    AdminReviewController.php
    AdminReviewController.php.before-toggle
    AnalyticsController.php
    AuthController.php
    AuthController.php.backup
    AuthController.php.before-my-shops-fix
    AuthController.php.before-superadmin-split
    BarcodeController.php
    CampaignController.php
    CampaignController.php.bak.1790436454
    CategoryController.php
    CompareController.php
    ContactMessageController.php
    Controller.php
    CouponController.php
    CustomerController.php
    DashboardApiController.php
    DashboardController.php
    DashboardController.php.before-superadmin-split
    DashboardNotificationController.php
    DashboardSmsLogController.php
    FlashSaleController.php
    InvoiceController.php
    LandingController.php
    LandingController.php.before-reviews
    LoyaltyController.php
    MaintenanceController.php
    NotificationController.php
    OrderController.php
    OrderTrackingController.php
    OwnerShopsController.php
    OwnerShopsController.php.before-owner-fix
    PaymentController.php
    PlatformReportController.php
    PriceAlertController.php
    ProductController.php
    ProfileController.php
    PushController.php

📁 app/Models/   (40 عنصر)
    ActivityLog.php
    AdminNotification.php
    Category.php
    Concerns/
    ContactMessage.php
    Coupon.php
    CustomerAddress.php
    CustomerEvent.php
    CustomerProfile.php
    EmailCampaign.php
    EmailCampaignRecipient.php
    FlashSale.php
    LoyaltyPoint.php
    LoyaltyTransaction.php
    Order.php
    OrderItem.php
    OrderStatusHistory.php
    PaymentTransaction.php
    PaymentWallet.php
    Plan.php
    PlatformInvoice.php
    PriceAlert.php
    Product.php
    Product.php.before-video
    ProductRecommendation.php
    ProductVariant.php
    ProductView.php
    PushSubscription.php
    Review.php
    ShippingZone.php
    Shop.php
    SmsInbox.php
    SmsLog.php
    SmsPattern.php
    SmsTemplate.php
    Subscription.php
    Task.php
    Testimonial.php
    User.php
    Wishlist.php

📁 app/Http/Middleware/   (6 عنصر)
    CheckMaintenance.php
    CheckPermission.php
    RequireSuperAdmin.php
    RequireSuperAdmin.php.before-guest-fix
    ResolveTenant.php
    ResolveTenant.php.before-superadmin-split

📁 app/Providers/   (1 عنصر)
    AppServiceProvider.php

📁 app/Services/   (13 عنصر)
    Analytics/
    Loyalty/
    Mail/
    Notifications/
    OneSignal/
    Payment/
    Push/
    Recommendation/
    Reports/
    ShopThemeDetector.php
    Sms/
    Telegram/
    Tenant/

📁 routes/   (17 عنصر)
    console.php
    web.php
    web.php.backup
    web.php.before-contact-reviews
    web.php.before-impersonate
    web.php.before-pwa-fix
    web.php.before-routes-cleanup
    web.php.before-shop-edit
    web.php.before-sms-center
    web.php.before-sms-templates
    web.php.before-sub-routes
    web.php.before-super-admin-middleware
    web.php.before-system-health
    web.php.before-testimonials
    web.php.before-user-mgmt-routes
    web.php.before-user-show-route
    web.php.before-users-route

📁 database/migrations/   (38 عنصر)
    0001_01_01_000000_create_users_table.php
    0001_01_01_000001_create_cache_table.php
    0001_01_01_000002_create_jobs_table.php
    2026_01_01_000100_create_saas_tables.php
    2026_01_02_000001_create_categories_reviews_wishlists.php
    2026_01_03_000001_create_coupons_table.php
    2026_01_04_000001_add_customer_fields.php
    2026_01_05_000001_create_shine_features.php
    2026_01_06_000001_create_billing_tables.php
    2026_01_07_000001_create_staff_tables.php
    2026_01_08_000001_create_recommendation_system.php
    2026_01_09_000001_create_order_tracking.php
    2026_01_11_000001_add_smtp_to_shops.php
    2026_01_12_000001_fix_history_table.php
    2026_09_22_182627_add_customer_rating_to_orders_table.php
    2026_09_23_000001_add_registration_fields_to_shops_table.php
    2026_09_23_100000_create_admin_notifications_table.php
    2026_09_24_142731_create_sms_logs_table.php
    2026_09_24_170407_create_push_subscriptions_table.php
    2026_09_25_145739_add_video_to_products_table.php
    2026_09_25_151538_add_sizes_colors_to_products_table.php
    2026_09_25_152145_create_product_variants_table.php
    2026_09_25_153801_add_color_hex_to_variants.php
    2026_09_25_173623_add_barcode_to_product_variants.php
    2026_09_25_213154_add_barcode_to_products_table.php
    2026_09_26_011738_add_variant_labels_to_products.php
    2026_09_26_023330_add_variant_columns_to_order_items.php
    2026_09_26_165652_add_advanced_fields_to_coupons.php
    2026_09_26_171552_add_points_to_orders.php
    2026_09_26_172949_add_coupon_to_orders.php
    2026_09_26_175520_create_email_campaigns_tables.php
    2026_09_27_200052_fix_sms_inbox_fk.php
    2026_09_27_221741_create_shipping_zones_table.php
    2026_09_27_223152_add_max_order_to_shipping_zones.php
    2026_09_27_223153_add_shipping_status_to_orders.php
    2026_09_28_170123_create_sms_templates_table.php
    2026_09_29_173554_create_contact_messages_table.php
    2026_09_29_174455_create_testimonials_table.php

📁 resources/views/   (17 عنصر)
    admin/
    auth/
    components/
    customer/
    dashboard/
    emails/
    errors/
    invoices/
    landing/
    layouts/
    maintenance.blade.php
    offline.blade.php
    owner/
    showcase.blade.php
    storefront/
    super-admin/
    welcome.blade.php

📁 resources/views/layouts/   (12 عنصر)
    app.blade.php
    app.blade.php.before-back-btn
    app.blade.php.before-back-btn2
    app.blade.php.before-impersonate-banner
    app.blade.php.before-messages-link
    app.blade.php.before-profile-link
    app.blade.php.before-testimonials-link
    dashboard.blade.php
    storefront.blade.php
    super-admin.blade.php
    super-admin.blade.php.before-back-btn
    super-admin.blade.php.before-users-link

📁 config/   (10 عنصر)
    app.php
    auth.php
    cache.php
    database.php
    filesystems.php
    logging.php
    mail.php
    queue.php
    services.php
    session.php


## 3. المسارات (Routes)

The "--columns" option does not exist.


## 4. Controllers + Methods


📄 ActivityLogController.php   [1 method]
    • index()

📄 AddressController.php   [5 method]
    • index()
    • store()
    • update()
    • destroy()
    • setDefault()

📄 AdminReviewController.php   [4 method]
    • index()
    • approve()
    • destroy()
    • toggleVisibility()

📄 AnalyticsController.php   [1 method]
    • index()

📄 AuthController.php   [7 method]
    • showLogin()
    • login()
    • showRegister()
    • register()
    • switchShop()
    • listShops()
    • logout()

📄 BarcodeController.php   [3 method]
    • scanner()
    • lookup()
    • quickUpdate()

📄 CampaignController.php   [6 method]
    • index()
    • create()
    • store()
    • show()
    • send()
    • destroy()

📄 CategoryController.php   [7 method]
    • index()
    • create()
    • store()
    • edit()
    • update()
    • destroy()
    • quickStore()

📄 CompareController.php   [3 method]
    • index()
    • toggle()
    • clear()

📄 ContactMessageController.php   [4 method]
    • store()
    • index()
    • show()
    • destroy()

📄 Controller.php   [0 method]

📄 CouponController.php   [7 method]
    • index()
    • create()
    • store()
    • edit()
    • update()
    • destroy()
    • validate_coupon()

📄 CustomerController.php   [11 method]
    • showLogin()
    • login()
    • showRegister()
    • register()
    • logout()
    • dashboard()
    • orders()
    • orderDetail()
    • profile()
    • updateProfile()
    • wishlist()

📄 DashboardApiController.php   [2 method]
    • stats()
    • recentOrders()

📄 DashboardController.php   [1 method]
    • index()

📄 DashboardNotificationController.php   [6 method]
    • index()
    • markRead()
    • markAllRead()
    • destroy()
    • clearAll()
    • count()

📄 DashboardSmsLogController.php   [4 method]
    • index()
    • retry()
    • destroy()
    • clearFailed()

📄 FlashSaleController.php   [4 method]
    • index()
    • create()
    • store()
    • destroy()

📄 InvoiceController.php   [1 method]
    • show()

📄 LandingController.php   [1 method]
    • index()

📄 LoyaltyController.php   [6 method]
    • index()
    • history()
    • redeem()
    • balance()
    • dashboard()
    • grantPoints()

📄 MaintenanceController.php   [2 method]
    • status()
    • toggle()

📄 NotificationController.php   [1 method]
    • check()

📄 OrderController.php   [3 method]
    • index()
    • show()
    • updateStatus()

📄 OrderTrackingController.php   [4 method]
    • publicTrackForm()
    • search()
    • show()
    • updateStatus()

📄 OwnerShopsController.php   [9 method]
    • index()
    • create()
    • store()
    • show()
    • edit()
    • update()
    • destroy()
    • toggleStatus()
    • extendTrial()

📄 PaymentController.php   [1 method]
    • index()

📄 PlatformReportController.php   [1 method]
    • index()

📄 PriceAlertController.php   [1 method]
    • store()

📄 ProductController.php   [8 method]
    • index()
    • create()
    • store()
    • show()
    • edit()
    • update()
    • destroy()
    • checkBarcode()

📄 ProfileController.php   [2 method]
    • showPasswordForm()
    • updatePassword()

📄 PushController.php   [4 method]
    • publicKey()
    • subscribe()
    • unsubscribe()
    • test()

📄 RecommendationStatsController.php   [1 method]
    • index()

📄 ReportController.php   [1 method]
    • index()

📄 ReportsController.php   [3 method]
    • sales()
    • exportSales()
    • exportTopProducts()

📄 ReviewController.php   [1 method]
    • store()

📄 SearchController.php   [1 method]
    • suggest()

📄 SettingsController.php   [4 method]
    • index()
    • update()
    • uploadLogo()
    • deleteLogo()

📄 ShippingController.php   [1 method]
    • calculate()

📄 ShippingZoneController.php   [9 method]
    • index()
    • create()
    • store()
    • edit()
    • update()
    • destroy()
    • toggle()
    • apiList()
    • apiCalculate()

📄 ShopManagementController.php   [6 method]
    • index()
    • create()
    • store()
    • destroy()
    • edit()
    • update()

📄 SitemapController.php   [1 method]
    • index()

📄 SmartCouponController.php   [2 method]
    • welcome()
    • nudge()

📄 SmartDashboardController.php   [1 method]
    • index()

📄 SmartNotificationController.php   [2 method]
    • check()
    • cartReminder()

📄 SmsCenterController.php   [1 method]
    • index()

📄 SmsInboxController.php   [4 method]
    • index()
    • show()
    • confirm()
    • reject()

📄 SmsTemplateController.php   [4 method]
    • index()
    • update()
    • reset()
    • preview()

📄 SmsWebhookController.php   [1 method]
    • handle()

📄 SmtpSettingsController.php   [2 method]
    • update()
    • test()

📄 StaffController.php   [6 method]
    • index()
    • create()
    • store()
    • edit()
    • update()
    • destroy()

📄 StorefrontController.php   [11 method]
    • index()
    • product()
    • cart()
    • addToCart()
    • updateCart()
    • removeFromCart()
    • checkout()
    • placeOrder()
    • orderSuccess()
    • trackOrder()
    • autocomplete()

📄 SubscriptionController.php   [4 method]
    • index()
    • assign()
    • cancel()
    • plans()

📄 SuperAdminController.php   [11 method]
    • index()
    • toggleStatus()
    • show()
    • impersonate()
    • stopImpersonating()
    • users()
    • showUser()
    • createUser()
    • storeUser()
    • showResetPassword()
    • resetPassword()

📄 SystemHealthController.php   [1 method]
    • index()

📄 TaskController.php   [4 method]
    • index()
    • store()
    • toggle()
    • destroy()

📄 TestMailController.php   [1 method]
    • send()

📄 TestimonialController.php   [4 method]
    • store()
    • index()
    • toggle()
    • destroy()

📄 TrackingController.php   [1 method]
    • track()

📄 VariantAnalyticsController.php   [4 method]
    • index()
    • exportDetails()
    • exportByColor()
    • exportBySize()

📄 VariantController.php   [5 method]
    • index()
    • update()
    • destroy()
    • bulk()
    • export()

📄 WishlistController.php   [4 method]
    • index()
    • toggle()
    • clear()
    • alert()


## 5. Models


📦 ActivityLog

📦 AdminNotification

📦 Category
    • products() → hasMany(Product::class)

📦 ContactMessage
    • fillable (8): shop_id, name, email, phone, subject, message, is_read, read_at
    • shop() → belongsTo(Shop::class)

📦 Coupon

📦 CustomerAddress

📦 CustomerEvent

📦 CustomerProfile
    • user() → belongsTo(User::class)

📦 EmailCampaign
    • recipients() → hasMany(EmailCampaignRecipient::class, 'campaign_id')
    • shop() → belongsTo(Shop::class)

📦 EmailCampaignRecipient
    • campaign() → belongsTo(EmailCampaign::class, 'campaign_id')
    • user() → belongsTo(User::class)

📦 FlashSale
    • product() → belongsTo(Product::class)

📦 LoyaltyPoint
    • user() → belongsTo(User::class)

📦 LoyaltyTransaction

📦 Order
    • items() → hasMany(OrderItem::class)
    • shop() → belongsTo(Shop::class)
    • statusHistory() → hasMany(OrderStatusHistory::class)

📦 OrderItem
    • order() → belongsTo(Order::class)
    • product() → belongsTo(Product::class)
    • variant() → belongsTo(ProductVariant::class, 'variant_id')

📦 OrderStatusHistory
    • order() → belongsTo(Order::class)
    • user() → belongsTo(User::class, 'changed_by')

📦 PaymentTransaction
    • order() → belongsTo(Order::class)
    • smsInbox() → belongsTo(SmsInbox::class, 'sms_inbox_id')
    • shop() → belongsTo(Shop::class)

📦 PaymentWallet

📦 Plan
    • subscriptions() → hasMany(Subscription::class)

📦 PlatformInvoice
    • shop() → belongsTo(Shop::class)
    • subscription() → belongsTo(Subscription::class)

📦 PriceAlert
    • product() → belongsTo(Product::class)
    • user() → belongsTo(User::class)

📦 Product
    • category() → belongsTo(Category::class)
    • variants() → hasMany(ProductVariant::class)

📦 ProductRecommendation
    • product() → belongsTo(Product::class)

📦 ProductVariant
    • product() → belongsTo(Product::class)

📦 ProductView
    • product() → belongsTo(Product::class)

📦 PushSubscription
    • user() → belongsTo(User::class)
    • shop() → belongsTo(Shop::class)

📦 Review
    • product() → belongsTo(Product::class)

📦 ShippingZone
    • shop() → belongsTo(Shop::class)

📦 Shop
    • users() → hasMany(User::class)
    • products() → hasMany(Product::class)
    • orders() → hasMany(Order::class)
    • wallets() → hasMany(PaymentWallet::class)
    • categories() → hasMany(Category::class)

📦 SmsInbox
    • table: sms_inbox
    • matchedOrder() → belongsTo(Order::class, 'matched_order_id')
    • shop() → belongsTo(Shop::class)
    • paymentTransaction() → hasOne(PaymentTransaction::class, 'sms_inbox_id')

📦 SmsLog
    • shop() → belongsTo(Shop::class)

📦 SmsPattern

📦 SmsTemplate
    • fillable (4): shop_id, event_key, body, is_active
    • shop() → belongsTo(Shop::class)

📦 Subscription
    • shop() → belongsTo(Shop::class)
    • plan() → belongsTo(Plan::class)

📦 Task
    • user() → belongsTo(User::class)

📦 Testimonial
    • fillable (6): name, role, rating, comment, is_visible, ip

📦 User
    • shop() → belongsTo(Shop::class)

📦 Wishlist
    • product() → belongsTo(Product::class)


## 6. Migrations

المجموع: 38 ملف

    0001_01_01_000000_create_users_table.php
    0001_01_01_000001_create_cache_table.php
    0001_01_01_000002_create_jobs_table.php
    2026_01_01_000100_create_saas_tables.php
    2026_01_02_000001_create_categories_reviews_wishlists.php
    2026_01_03_000001_create_coupons_table.php
    2026_01_04_000001_add_customer_fields.php
    2026_01_05_000001_create_shine_features.php
    2026_01_06_000001_create_billing_tables.php
    2026_01_07_000001_create_staff_tables.php
    2026_01_08_000001_create_recommendation_system.php
    2026_01_09_000001_create_order_tracking.php
    2026_01_11_000001_add_smtp_to_shops.php
    2026_01_12_000001_fix_history_table.php
    2026_09_22_182627_add_customer_rating_to_orders_table.php
    2026_09_23_000001_add_registration_fields_to_shops_table.php
    2026_09_23_100000_create_admin_notifications_table.php
    2026_09_24_142731_create_sms_logs_table.php
    2026_09_24_170407_create_push_subscriptions_table.php
    2026_09_25_145739_add_video_to_products_table.php
    2026_09_25_151538_add_sizes_colors_to_products_table.php
    2026_09_25_152145_create_product_variants_table.php
    2026_09_25_153801_add_color_hex_to_variants.php
    2026_09_25_173623_add_barcode_to_product_variants.php
    2026_09_25_213154_add_barcode_to_products_table.php
    2026_09_26_011738_add_variant_labels_to_products.php
    2026_09_26_023330_add_variant_columns_to_order_items.php
    2026_09_26_165652_add_advanced_fields_to_coupons.php
    2026_09_26_171552_add_points_to_orders.php
    2026_09_26_172949_add_coupon_to_orders.php
    2026_09_26_175520_create_email_campaigns_tables.php
    2026_09_27_200052_fix_sms_inbox_fk.php
    2026_09_27_221741_create_shipping_zones_table.php
    2026_09_27_223152_add_max_order_to_shipping_zones.php
    2026_09_27_223153_add_shipping_status_to_orders.php
    2026_09_28_170123_create_sms_templates_table.php
    2026_09_29_173554_create_contact_messages_table.php
    2026_09_29_174455_create_testimonials_table.php


## 7. Schema الفعلي (SQLite)

عدد الجداول: 46


📋 activity_logs
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • user_id  (INTEGER)
    • user_name  (varchar)
    • action  (varchar)
    • subject_type  (varchar)
    • subject_id  (INTEGER)
    • description  (varchar)
    • meta  (TEXT)
    • ip_address  (varchar)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 loyalty_points
    • id  (INTEGER)
    • user_id  (INTEGER)
    • shop_id  (INTEGER)
    • balance  (INTEGER)
    • total_earned  (INTEGER)
    • total_redeemed  (INTEGER)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 push_subscriptions
    • id  (INTEGER)
    • user_id  (INTEGER)
    • shop_id  (INTEGER)
    • endpoint  (TEXT)
    • public_key  (varchar)
    • auth_token  (varchar)
    • content_encoding  (varchar)
    • user_agent  (varchar)
    • last_used_at  (datetime)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 admin_notifications
    • id  (INTEGER)
    • type  (varchar)
    • title  (varchar)
    • message  (TEXT)
    • data  (TEXT)
    • is_read  (tinyint(1))
    • read_at  (datetime)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 loyalty_transactions
    • id  (INTEGER)
    • user_id  (INTEGER)
    • points  (INTEGER)
    • type  (varchar)
    • reason  (varchar)
    • reference_id  (INTEGER)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 reviews
    • id  (INTEGER)
    • product_id  (INTEGER)
    • shop_id  (INTEGER)
    • customer_name  (varchar)
    • customer_phone  (varchar)
    • rating  (INTEGER)
    • comment  (TEXT)
    • is_approved  (tinyint(1))
    • created_at  (datetime)
    • updated_at  (datetime)
    • image  (varchar)
    • helpful_count  (INTEGER)

📋 cache
    • key  (varchar)
    • value  (TEXT)
    • expiration  (INTEGER)

📋 migrations
    • id  (INTEGER)
    • migration  (varchar)
    • batch  (INTEGER)

📋 sessions
    • id  (varchar)
    • user_id  (INTEGER)
    • ip_address  (varchar)
    • user_agent  (TEXT)
    • payload  (TEXT)
    • last_activity  (INTEGER)

📋 cache_locks
    • key  (varchar)
    • owner  (varchar)
    • expiration  (INTEGER)

📋 order_items
    • id  (INTEGER)
    • order_id  (INTEGER)
    • product_id  (INTEGER)
    • product_name  (varchar)
    • unit_price  (numeric)
    • quantity  (INTEGER)
    • line_total  (numeric)
    • variant_id  (INTEGER)
    • color  (varchar)
    • color_hex  (varchar)
    • size  (varchar)

📋 shipping_zones
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • name  (varchar)
    • code  (varchar)
    • fee  (numeric)
    • free_over  (numeric)
    • min_order  (numeric)
    • eta  (varchar)
    • is_active  (tinyint(1))
    • sort_order  (INTEGER)
    • created_at  (datetime)
    • updated_at  (datetime)
    • max_order_amount  (numeric)

📋 categories
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • name  (varchar)
    • slug  (varchar)
    • created_at  (datetime)
    • updated_at  (datetime)
    • icon  (varchar)
    • description  (TEXT)
    • is_active  (tinyint(1))

📋 order_status_histories
    • id  (INTEGER)
    • order_id  (INTEGER)
    • shop_id  (INTEGER)
    • from_status  (varchar)
    • to_status  (varchar)
    • note  (TEXT)
    • changed_by  (INTEGER)
    • changed_by_name  (varchar)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 shops
    • id  (INTEGER)
    • name  (varchar)
    • slug  (varchar)
    • custom_domain  (varchar)
    • logo  (varchar)
    • primary_color  (varchar)
    • phone  (varchar)
    • whatsapp  (varchar)
    • currency  (varchar)
    • locale  (varchar)
    • webhook_token  (varchar)
    • status  (varchar)
    • trial_ends_at  (datetime)
    • settings  (TEXT)
    • created_at  (datetime)
    • updated_at  (datetime)
    • smtp_settings  (TEXT)
    • email  (varchar)
    • address  (TEXT)
    • city  (varchar)
    • country  (varchar)
    • description  (TEXT)

📋 contact_messages
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • name  (varchar)
    • email  (varchar)
    • phone  (varchar)
    • subject  (varchar)
    • message  (TEXT)
    • is_read  (tinyint(1))
    • read_at  (datetime)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 orders
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • order_number  (varchar)
    • customer_name  (varchar)
    • customer_phone  (varchar)
    • customer_address  (varchar)
    • notes  (TEXT)
    • subtotal  (numeric)
    • shipping  (numeric)
    • total  (numeric)
    • currency  (varchar)
    • payment_method  (varchar)
    • payment_status  (varchar)
    • status  (varchar)
    • payment_reference  (varchar)
    • paid_at  (datetime)
    • created_at  (datetime)
    • updated_at  (datetime)
    • user_id  (INTEGER)
    • tracking_number  (varchar)
    • tracking_url  (varchar)
    • carrier  (varchar)
    • shipped_at  (datetime)
    • delivered_at  (datetime)
    • customer_rating  (INTEGER)
    • customer_email  (varchar)
    • customer_note  (TEXT)
    • points_used  (INTEGER)
    • points_value  (numeric)
    • coupon_code  (varchar)
    • discount  (numeric)
    • shipping_status  (varchar)
    • shipping_note  (TEXT)
    • shipping_quoted_at  (datetime)

📋 sms_inbox
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • sender_phone  (varchar)
    • raw_body  (TEXT)
    • parsed_amount  (numeric)
    • parsed_sender  (varchar)
    • parsed_reference  (varchar)
    • provider  (varchar)
    • confidence  (INTEGER)
    • matched_order_id  (INTEGER)
    • status  (varchar)
    • received_at  (datetime)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 coupons
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • code  (varchar)
    • type  (varchar)
    • value  (numeric)
    • min_order  (numeric)
    • max_uses  (INTEGER)
    • used_count  (INTEGER)
    • expires_at  (datetime)
    • is_active  (tinyint(1))
    • created_at  (datetime)
    • updated_at  (datetime)
    • max_discount  (numeric)
    • per_user_limit  (INTEGER)
    • first_order_only  (tinyint(1))
    • applies_to  (varchar)
    • applies_to_id  (INTEGER)
    • starts_at  (datetime)
    • description  (varchar)
    • min_qty  (INTEGER)

📋 password_reset_tokens
    • email  (varchar)
    • token  (varchar)
    • created_at  (datetime)

📋 sms_logs
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • to  (varchar)
    • message  (TEXT)
    • provider  (varchar)
    • status  (varchar)
    • external_id  (varchar)
    • error  (TEXT)
    • meta  (TEXT)
    • sent_at  (datetime)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 customer_addresses
    • id  (INTEGER)
    • user_id  (INTEGER)
    • label  (varchar)
    • name  (varchar)
    • phone  (varchar)
    • city  (varchar)
    • area  (varchar)
    • address  (TEXT)
    • is_default  (tinyint(1))
    • created_at  (datetime)
    • updated_at  (datetime)

📋 payment_transactions
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • order_id  (INTEGER)
    • sms_inbox_id  (INTEGER)
    • provider  (varchar)
    • amount  (numeric)
    • sender_phone  (varchar)
    • reference_number  (varchar)
    • status  (varchar)
    • verified_by  (varchar)
    • verified_at  (datetime)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 sms_patterns
    • id  (INTEGER)
    • provider  (varchar)
    • label  (varchar)
    • amount_regex  (varchar)
    • sender_regex  (varchar)
    • reference_regex  (varchar)
    • keywords  (TEXT)
    • is_active  (tinyint(1))
    • created_at  (datetime)
    • updated_at  (datetime)

📋 customer_events
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • user_id  (INTEGER)
    • session_id  (varchar)
    • event_type  (varchar)
    • product_id  (INTEGER)
    • category_id  (INTEGER)
    • price_at_event  (numeric)
    • search_query  (varchar)
    • duration_seconds  (INTEGER)
    • meta  (TEXT)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 payment_wallets
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • provider  (varchar)
    • wallet_number  (varchar)
    • holder_name  (varchar)
    • is_active  (tinyint(1))
    • created_at  (datetime)
    • updated_at  (datetime)

📋 sms_templates
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • event_key  (varchar)
    • body  (varchar)
    • is_active  (tinyint(1))
    • created_at  (datetime)
    • updated_at  (datetime)

📋 customer_profiles
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • user_id  (INTEGER)
    • session_id  (varchar)
    • preferred_categories  (TEXT)
    • preferred_price_range  (TEXT)
    • top_viewed_products  (TEXT)
    • total_views  (INTEGER)
    • total_cart_adds  (INTEGER)
    • total_purchases  (INTEGER)
    • total_spent  (numeric)
    • last_activity_at  (datetime)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 plans
    • id  (INTEGER)
    • name  (varchar)
    • slug  (varchar)
    • description  (TEXT)
    • price  (numeric)
    • interval  (varchar)
    • features  (TEXT)
    • limits  (TEXT)
    • is_active  (tinyint(1))
    • sort_order  (INTEGER)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 subscriptions
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • plan_id  (INTEGER)
    • status  (varchar)
    • starts_at  (datetime)
    • ends_at  (datetime)
    • trial_ends_at  (datetime)
    • cancelled_at  (datetime)
    • payment_method  (varchar)
    • amount_paid  (numeric)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 email_campaign_recipients
    • id  (INTEGER)
    • campaign_id  (INTEGER)
    • user_id  (INTEGER)
    • email  (varchar)
    • name  (varchar)
    • status  (varchar)
    • error_message  (TEXT)
    • sent_at  (datetime)
    • opened_at  (datetime)
    • clicked_at  (datetime)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 platform_invoices
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • subscription_id  (INTEGER)
    • invoice_number  (varchar)
    • amount  (numeric)
    • status  (varchar)
    • due_date  (datetime)
    • paid_at  (datetime)
    • payment_reference  (varchar)
    • notes  (TEXT)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 tasks
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • user_id  (INTEGER)
    • title  (varchar)
    • description  (TEXT)
    • priority  (varchar)
    • status  (varchar)
    • due_date  (datetime)
    • completed_at  (datetime)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 email_campaigns
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • name  (varchar)
    • subject  (varchar)
    • body  (TEXT)
    • template  (varchar)
    • status  (varchar)
    • target_type  (varchar)
    • target_value  (varchar)
    • recipients_count  (INTEGER)
    • sent_count  (INTEGER)
    • failed_count  (INTEGER)
    • opens_count  (INTEGER)
    • clicks_count  (INTEGER)
    • scheduled_at  (datetime)
    • sent_at  (datetime)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 price_alerts
    • id  (INTEGER)
    • product_id  (INTEGER)
    • user_id  (INTEGER)
    • email  (varchar)
    • phone  (varchar)
    • target_price  (numeric)
    • notified  (tinyint(1))
    • created_at  (datetime)
    • updated_at  (datetime)

📋 testimonials
    • id  (INTEGER)
    • name  (varchar)
    • role  (varchar)
    • rating  (INTEGER)
    • comment  (TEXT)
    • is_visible  (tinyint(1))
    • ip  (varchar)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 failed_jobs
    • id  (INTEGER)
    • uuid  (varchar)
    • connection  (varchar)
    • queue  (varchar)
    • payload  (TEXT)
    • exception  (TEXT)
    • failed_at  (datetime)

📋 product_recommendations
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • product_id  (INTEGER)
    • similar_product_ids  (TEXT)
    • bought_together_ids  (TEXT)
    • view_count  (INTEGER)
    • cart_count  (INTEGER)
    • purchase_count  (INTEGER)
    • conversion_rate  (numeric)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 users
    • id  (INTEGER)
    • name  (varchar)
    • email  (varchar)
    • email_verified_at  (datetime)
    • password  (varchar)
    • remember_token  (varchar)
    • created_at  (datetime)
    • updated_at  (datetime)
    • shop_id  (INTEGER)
    • role  (varchar)
    • phone  (varchar)
    • address  (varchar)
    • city  (varchar)
    • avatar  (varchar)
    • last_login_at  (datetime)
    • permissions  (TEXT)
    • created_by  (INTEGER)
    • is_active  (tinyint(1))

📋 flash_sales
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • product_id  (INTEGER)
    • discount_price  (numeric)
    • max_qty  (INTEGER)
    • sold_qty  (INTEGER)
    • starts_at  (datetime)
    • ends_at  (datetime)
    • is_active  (tinyint(1))
    • created_at  (datetime)
    • updated_at  (datetime)

📋 product_variants
    • id  (INTEGER)
    • product_id  (INTEGER)
    • size  (varchar)
    • color  (varchar)
    • stock  (INTEGER)
    • price  (numeric)
    • sku  (varchar)
    • is_active  (tinyint(1))
    • created_at  (datetime)
    • updated_at  (datetime)
    • color_hex  (varchar)
    • barcode  (varchar)

📋 wishlists
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • product_id  (INTEGER)
    • session_id  (varchar)
    • created_at  (datetime)
    • updated_at  (datetime)
    • user_id  (INTEGER)

📋 job_batches
    • id  (varchar)
    • name  (varchar)
    • total_jobs  (INTEGER)
    • pending_jobs  (INTEGER)
    • failed_jobs  (INTEGER)
    • failed_job_ids  (TEXT)
    • options  (TEXT)
    • cancelled_at  (INTEGER)
    • created_at  (INTEGER)
    • finished_at  (INTEGER)

📋 product_views
    • id  (INTEGER)
    • product_id  (INTEGER)
    • session_id  (varchar)
    • created_at  (datetime)
    • updated_at  (datetime)

📋 jobs
    • id  (INTEGER)
    • queue  (varchar)
    • payload  (TEXT)
    • attempts  (INTEGER)
    • reserved_at  (INTEGER)
    • available_at  (INTEGER)
    • created_at  (INTEGER)

📋 products
    • id  (INTEGER)
    • shop_id  (INTEGER)
    • category_id  (INTEGER)
    • name  (varchar)
    • slug  (varchar)
    • description  (TEXT)
    • price  (numeric)
    • compare_price  (numeric)
    • stock  (INTEGER)
    • image  (varchar)
    • images  (TEXT)
    • is_active  (tinyint(1))
    • created_at  (datetime)
    • updated_at  (datetime)
    • sold_count  (INTEGER)
    • views_count  (INTEGER)
    • video  (varchar)
    • video_poster  (varchar)
    • sizes  (TEXT)
    • colors  (TEXT)
    • barcode  (varchar)
    • variant_label_1  (varchar)
    • variant_label_2  (varchar)


## 8. Views (Blade)


📁 resources/views/(root)/   (4)
    • maintenance.blade.php
    • offline.blade.php
    • showcase.blade.php
    • welcome.blade.php

📁 resources/views/landing/   (1)
    • index.blade.php

📁 resources/views/dashboard/   (3)
    • _maintenance.blade.php
    • index.blade.php
    • smart.blade.php

📁 resources/views/dashboard/products/   (4)
    • create.blade.php
    • edit.blade.php
    • index.blade.php
    • show.blade.php

📁 resources/views/dashboard/orders/   (2)
    • index.blade.php
    • show.blade.php

📁 resources/views/dashboard/sms/   (2)
    • index.blade.php
    • show.blade.php

📁 resources/views/dashboard/payments/   (1)
    • index.blade.php

📁 resources/views/dashboard/settings/   (2)
    • _smtp.blade.php
    • index.blade.php

📁 resources/views/dashboard/categories/   (3)
    • create.blade.php
    • edit.blade.php
    • index.blade.php

📁 resources/views/dashboard/coupons/   (3)
    • create.blade.php
    • edit.blade.php
    • index.blade.php

📁 resources/views/dashboard/reports/   (2)
    • index.blade.php
    • sales.blade.php

📁 resources/views/dashboard/reviews/   (1)
    • index.blade.php

📁 resources/views/dashboard/flash-sales/   (2)
    • create.blade.php
    • index.blade.php

📁 resources/views/dashboard/staff/   (3)
    • create.blade.php
    • edit.blade.php
    • index.blade.php

📁 resources/views/dashboard/activity-logs/   (1)
    • index.blade.php

📁 resources/views/dashboard/tasks/   (1)
    • index.blade.php

📁 resources/views/dashboard/recommendations/   (1)
    • index.blade.php

📁 resources/views/dashboard/loyalty/   (1)
    • index.blade.php

📁 resources/views/dashboard/analytics/   (4)
    • index.blade.php
    • overall.blade.php
    • variants-unified.blade.php
    • variants.blade.php

📁 resources/views/dashboard/notifications/   (1)
    • index.blade.php

📁 resources/views/dashboard/sms-logs/   (1)
    • index.blade.php

📁 resources/views/dashboard/scanner/   (1)
    • index.blade.php

📁 resources/views/dashboard/variants/   (1)
    • index.blade.php

📁 resources/views/dashboard/campaigns/   (3)
    • create.blade.php
    • index.blade.php
    • show.blade.php

📁 resources/views/dashboard/sms-templates/   (1)
    • index.blade.php

📁 resources/views/dashboard/sms-center/   (1)
    • index.blade.php

📁 resources/views/dashboard/sms-center/partials/   (3)
    • _inbox.blade.php
    • _logs.blade.php
    • _templates.blade.php

📁 resources/views/dashboard/shipping-zones/   (3)
    • create.blade.php
    • edit.blade.php
    • index.blade.php

📁 resources/views/dashboard/profile/   (1)
    • password.blade.php

📁 resources/views/dashboard/contact-messages/   (2)
    • index.blade.php
    • show.blade.php

📁 resources/views/dashboard/testimonials/   (1)
    • index.blade.php

📁 resources/views/auth/   (3)
    • login.blade.php
    • register.blade.php
    • shops.blade.php

📁 resources/views/layouts/   (4)
    • app.blade.php
    • dashboard.blade.php
    • storefront.blade.php
    • super-admin.blade.php

📁 resources/views/storefront/   (21)
    • _also-bought.blade.php
    • _categories.blade.php
    • _filters.blade.php
    • _flash.blade.php
    • _recommendations.blade.php
    • _social.blade.php
    • cart.blade.php
    • checkout.blade.php
    • compare.blade.php
    • contact.blade.php
    • index.blade.php
    • no-shop.blade.php
    • orders.blade.php
    • product.blade.php
    • success.blade.php
    • track-form.blade.php
    • track-order.blade.php
    • track-results.blade.php
    • track-show.blade.php
    • track.blade.php
    • wishlist.blade.php

📁 resources/views/customer/   (10)
    • addresses.blade.php
    • dashboard.blade.php
    • login.blade.php
    • loyalty-history.blade.php
    • loyalty.blade.php
    • order-detail.blade.php
    • orders.blade.php
    • profile.blade.php
    • register.blade.php
    • wishlist.blade.php

📁 resources/views/invoices/   (1)
    • order.blade.php

📁 resources/views/emails/   (1)
    • order-confirmed.blade.php

📁 resources/views/super-admin/   (4)
    • index.blade.php
    • reports.blade.php
    • show.blade.php
    • system-health.blade.php

📁 resources/views/super-admin/subscriptions/   (2)
    • index.blade.php
    • plans.blade.php

📁 resources/views/super-admin/shops/   (3)
    • create.blade.php
    • edit.blade.php
    • index.blade.php

📁 resources/views/super-admin/users/   (4)
    • create.blade.php
    • index.blade.php
    • reset-password.blade.php
    • show.blade.php

📁 resources/views/components/   (10)
    • floating-actions.blade.php
    • floating-cart.blade.php
    • product-3d.blade.php
    • product-circular-gallery.blade.php
    • product-gallery.blade.php
    • recently-viewed.blade.php
    • shop-logo.blade.php
    • toast.blade.php
    • whatsapp-button.blade.php
    • whatsapp-fab.blade.php

📁 resources/views/owner/shops/   (4)
    • create.blade.php
    • edit.blade.php
    • index.blade.php
    • show.blade.php

📁 resources/views/admin/   (1)
    • notifications.blade.php

📁 resources/views/errors/   (8)
    • 403.blade.php
    • 404.blade.php
    • 405.blade.php
    • 419.blade.php
    • 429.blade.php
    • 500.blade.php
    • 503.blade.php
    • _layout.blade.php


## 9. Middleware

    • CheckMaintenance.php
    • CheckPermission.php
    • RequireSuperAdmin.php
    • ResolveTenant.php


## 10. Service Providers

    • AppServiceProvider.php


## 11. Composer Packages (require)

    • laravel/framework: ^13.17
    • laravel/tinker: ^3.0
    • minishlink/web-push: ^9.0
    • mpdf/mpdf: ^6.1
    • php: ^8.3


## 12. ENV Keys (بدون قيم)

    • APP_NAME
    • APP_ENV
    • APP_KEY
    • APP_DEBUG
    • APP_URL
    • APP_LOCALE
    • APP_FALLBACK_LOCALE
    • APP_FAKER_LOCALE
    • APP_MAINTENANCE_DRIVER
    • BCRYPT_ROUNDS
    • LOG_CHANNEL
    • LOG_STACK
    • LOG_DEPRECATIONS_CHANNEL
    • LOG_LEVEL
    • DB_CONNECTION
    • SESSION_DRIVER
    • SESSION_LIFETIME
    • SESSION_ENCRYPT
    • SESSION_PATH
    • SESSION_DOMAIN
    • BROADCAST_CONNECTION
    • FILESYSTEM_DISK
    • QUEUE_CONNECTION
    • CACHE_STORE
    • MEMCACHED_HOST
    • REDIS_CLIENT
    • REDIS_HOST
    • REDIS_PASSWORD
    • REDIS_PORT
    • AWS_ACCESS_KEY_ID
    • AWS_SECRET_ACCESS_KEY
    • AWS_DEFAULT_REGION
    • AWS_BUCKET
    • AWS_USE_PATH_STYLE_ENDPOINT
    • VITE_APP_NAME
    • MAIL_MAILER
    • MAIL_HOST
    • MAIL_PORT
    • MAIL_USERNAME
    • MAIL_PASSWORD
    • MAIL_ENCRYPTION
    • MAIL_FROM_ADDRESS
    • MAIL_FROM_NAME
    • SMS_PROVIDER
    • TELEGRAM_BOT_TOKEN
    • TELEGRAM_CHAT_ID
    • VAPID_PUBLIC_KEY
    • VAPID_PRIVATE_KEY
    • VAPID_SUBJECT
    • ONESIGNAL_APP_ID
    • ONESIGNAL_REST_API_KEY


## 13. Layouts

    • app.blade.php
    • dashboard.blade.php
    • storefront.blade.php
    • super-admin.blade.php


## 📊 إحصائيات سريعة

• Controllers: 62
• Models:      38
• Migrations:  38
• Views:       136
• Routes:      249
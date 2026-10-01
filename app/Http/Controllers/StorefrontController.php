<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StorefrontController extends Controller
{
    private function shop()
    {
        return app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
    }

    private function currentShopId(): ?int
    {
        return $this->shop()?->id;
    }

    private function wishlistIds(): array
    {
        $shop = $this->shop();
        if (!$shop) return [];

        return Wishlist::withoutGlobalScope("tenant")
            ->where("session_id", session("wishlist_id"))
            ->where("shop_id", $shop->id)
            ->pluck("product_id")
            ->toArray();
    }

    public function index(Request $request)
    {
        $shop = $this->shop();
        if (!$shop) return view('storefront.no-shop');

        $query = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->where('is_active', true);

        // فلترة بالتصنيف
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // البحث بالاسم أو الوصف
        if ($q = trim((string) $request->get('q'))) {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // فلترة بالسعر
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // الترتيب
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            'best' => $query->orderByDesc('sold_count')->orderByDesc('id'),
            default => $query->latest(),
        };

        $products = $query->get();
        $categories = Category::withoutGlobalScope('tenant')->where('shop_id', $shop->id)->where('is_active', true)->get();

        $productsJson = $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'desc' => $p->description ?? '',
            'stock' => (int) $p->stock,
            'price' => (float) $p->price,
            'image' => $p->image,
            'category_id' => $p->category_id,
        ])->values();

        $wishlist = $this->wishlistIds();

        return view('storefront.index', compact('shop', 'products', 'productsJson', 'categories', 'wishlist'));
    }

    public function product($id)
    {
                $shop = $this->shop();
        if (!$shop) abort(404, 'لا يوجد متجر محدد.');
        $product = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->findOrFail($id);

        // التقييمات
        $reviews = Review::where('product_id', $id)->where('is_approved', true)->latest()->take(20)->get();

        // إحصائيات التقييمات
        $ratingStats = [
            'avg' => $reviews->avg('rating') ?: 0,
            'count' => $reviews->count(),
            'bars' => [
                5 => $reviews->where('rating', 5)->count(),
                4 => $reviews->where('rating', 4)->count(),
                3 => $reviews->where('rating', 3)->count(),
                2 => $reviews->where('rating', 2)->count(),
                1 => $reviews->where('rating', 1)->count(),
            ],
        ];

        // منتجات مشابهة — من نفس التصنيف أولاً، ثم نفس المتجر
        $relatedQuery = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $product->shop_id)
            ->where('id', '!=', $id)
            ->where('is_active', true);

        if ($product->category_id) {
            $relatedQuery->where('category_id', $product->category_id);
        }
        $related = (clone $relatedQuery)->latest()->take(8)->get();

        // إذا لم توجد بنفس التصنيف — من نفس المتجر
        if ($related->isEmpty()) {
            $related = Product::withoutGlobalScope('tenant')
                ->where('shop_id', $product->shop_id)
                ->where('id', '!=', $id)
                ->where('is_active', true)
                ->latest()
                ->take(8)
                ->get();
        }

        $wishlist = $this->wishlistIds();
        $inWishlist = in_array($id, $wishlist);

        // 🎨 الـ variants والألوان والمقاسات
        $variants = $product->variants()->where('is_active', true)->orderBy('id')->get();

        $colorsMap = [];
        $sizesMap = [];
        $variantsMap = [];

        foreach ($variants as $v) {
            if ($v->color) {
                if (!isset($colorsMap[$v->color])) {
                    $colorsMap[$v->color] = [
                        'name' => $v->color,
                        'hex' => $v->color_hex ?? '#000000',
                        'stock' => 0,
                    ];
                }
                $colorsMap[$v->color]['stock'] += (int)$v->stock;
            }
            if ($v->size) {
                if (!isset($sizesMap[$v->size])) {
                    $sizesMap[$v->size] = ['name' => $v->size, 'stock' => 0];
                }
                $sizesMap[$v->size]['stock'] += (int)$v->stock;
            }
            $key = ($v->color ?? '') . '|' . ($v->size ?? '');
            $variantsMap[$key] = [
                'id' => $v->id,
                'size' => $v->size,
                'color' => $v->color,
                'color_hex' => $v->color_hex ?? '#000000',
                'stock' => (int)$v->stock,
                'price' => $v->price,
                'sku' => $v->sku,
            ];
        }

        $colors = array_values($colorsMap);
        $sizes = array_values($sizesMap);
        $hasVariants = $variants->count() > 0;

        return view('storefront.product', compact('product', 'shop', 'reviews', 'related', 'inWishlist', 'ratingStats', 'variants', 'colors', 'sizes', 'variantsMap', 'hasVariants'));
    }

    public function cart()
    {
        $shop = $this->shop();
        $cart = session('cart', []);
        if (!$shop) return view('storefront.cart', ['items' => [], 'total' => 0]);
        $items = [];
        $total = 0;

        // ═══ Performance: جمع المعرفات أولاً لتجنب N+1 ═══
        $productIds = [];
        $variantIds = [];
        foreach ($cart as $key => $entry) {
            if (!is_array($entry)) { $productIds[] = (int) $key; continue; }
            if (!empty($entry['product_id'])) $productIds[] = (int) $entry['product_id'];
            if (!empty($entry['variant_id'])) $variantIds[] = (int) $entry['variant_id'];
        }
        $productIds = array_values(array_unique($productIds));
        $variantIds = array_values(array_unique($variantIds));

        $productsMap = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->whereIn('id', $productIds)->get()->keyBy('id');

        $variantsMap = !empty($variantIds)
            ? \App\Models\ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id')
            : collect();

        foreach ($cart as $key => $entry) {
            if (!is_array($entry)) {
                $entry = ['product_id' => (int) $key, 'variant_id' => null, 'qty' => (int) $entry, 'color' => null, 'size' => null];
            }
            $productId = $entry['product_id'] ?? null;
            if (!$productId) continue;

            $product = $productsMap->get($productId);
            if (!$product) continue;

            $variant = null;
            if (!empty($entry['variant_id'])) {
                $variant = $variantsMap->get($entry['variant_id']);
            }

            $qty = max(1, (int) ($entry['qty'] ?? 1));
            $price = $variant && $variant->price ? (float) $variant->price : (float) $product->price;
            $subtotal = $price * $qty;

            $items[] = [
                'key' => $key, 'product' => $product, 'variant' => $variant,
                'variant_id' => $entry['variant_id'] ?? null,
                'color' => $entry['color'] ?? ($variant->color ?? null),
                'size' => $entry['size'] ?? ($variant->size ?? null),
                'qty' => $qty, 'price' => $price, 'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }

        return view('storefront.cart', compact('items', 'total'));
    }

    public function addToCart(Request $request, $id)
    {
        $shop = $this->shop();
        if (!$shop) abort(404, 'لا يوجد متجر محدد.');
        $cartShopId = session('cart_shop_id');
        if ($cartShopId && (int) $cartShopId !== (int) $shop->id) {
            return response()->json(['success' => false, 'message' => 'السلة مرتبطة بمتجر آخر. أفرغ السلة أولاً.'], 409);
        }
        $product = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->where('is_active', true)
            ->findOrFail($id);
        $qty = max(1, (int) $request->input('qty', 1));
        $variantId = $request->input('variant_id');
        $color = $request->input('selected_color');
        $size = $request->input('selected_size');

        // 🛡️ تحقق من السيرفر: المنتج عنده variants؟
        $hasVariants = $product->variants()->where('is_active', true)->exists();

        if ($hasVariants && !$variantId) {
            // المنتج عنده variants لكن لم يُرسَل variant_id
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'الرجاء اختيار المتغيرات (اللون/المقاس) قبل الإضافة',
                ], 422);
            }
            return back()->with('error', 'الرجاء اختيار المتغيرات قبل الإضافة');
        }

        // إن كان هناك variant، نستخدمه
        $variant = null;
        if ($variantId) {
            $variant = \App\Models\ProductVariant::where('product_id', $id)
                ->where('id', $variantId)
                ->first();

            // 🛡️ التحقق من أن الـ variant يخص هذا المنتج فعلاً
            if (!$variant) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'المتغير المختار غير صالح',
                    ], 422);
                }
                return back()->with('error', 'المتغير المختار غير صالح');
            }
        }

        // المفتاح الفريد: pid أو pid::vid
        $cartKey = $variantId ? ($id . '::' . $variantId) : (string) $id;

        $cart = session('cart', []);

        // ═══ Backward compat: تحويل المدخلات القديمة { pid: qty } إلى الشكل الجديد { key: {product_id, variant_id, qty, color, size} } ═══
        foreach ($cart as $k => $v) {
            if (!is_array($v)) {
                $cart[$k] = [
                    'product_id' => (int) $k,
                    'variant_id' => null,
                    'qty' => (int) $v,
                    'color' => null,
                    'size' => null,
                ];
            }
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $qty;
        } else {
            $cart[$cartKey] = [
                'product_id' => (int) $id,
                'variant_id' => $variant ? $variant->id : null,
                'qty' => $qty,
                'color' => $variant ? $variant->color : $color,
                'size' => $variant ? $variant->size : $size,
            ];
        }

        session(['cart' => $cart, 'cart_shop_id' => $shop->id]);

        // إذا كان AJAX — أعد JSON
        if ($request->expectsJson() || $request->ajax()) {
            $count = (function($c){ $s=0; foreach($c as $e){ $s += is_array($e) ? (int)($e["qty"]??0) : (int)$e; } return $s; })($cart);
            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => 'تمت إضافة المنتج للسلة 🎉',
                'animate' => true,
            ]);
        }

        // إذا كان طلب عادي — أعد توجيه مع رسالة
        return back()->with('success', '✅ تمت إضافة المنتج للسلة');
    }

    public function updateCart(Request $request, $id)
    {
        $qty = (int) $request->input('qty', 1);
        $cart = session('cart', []);

        // نقبل variantKey أو productId
        $key = $id;
        if (!isset($cart[$key])) {
            // ابحث بالـ product_id في البنية الجديدة
            foreach ($cart as $k => $entry) {
                if (is_array($entry) && (int)$entry['product_id'] === (int)$id) {
                    $key = $k;
                    break;
                }
            }
        }

        if (!isset($cart[$key])) {
            return redirect('/cart');
        }

        if ($qty <= 0) {
            unset($cart[$key]);
        } else {
            if (is_array($cart[$key])) {
                $cart[$key]['qty'] = $qty;
            } else {
                $cart[$key] = $qty;
            }
        }
        session(['cart' => $cart]);
        return redirect('/cart');
    }

    public function removeFromCart($id)
    {
        $cart = session('cart', []);

        // نقبل variantKey أو productId
        $key = $id;
        if (!isset($cart[$key])) {
            foreach ($cart as $k => $entry) {
                if (is_array($entry) && (int)$entry['product_id'] === (int)$id) {
                    $key = $k;
                    break;
                }
            }
        }
        unset($cart[$key]);
        session(['cart' => $cart]);
        return redirect('/cart')->with('success', 'تم حذف المنتج');
    }

    public function checkout()
    {
        $cart = session('cart', []);
        $shop = app(\App\Services\Tenant\TenantManager::class)->get();

        if (!$shop) {
            abort(404, 'لا يوجد متجر محدد لهذا الطلب.');
        }

        $items = [];
        $total = 0;

        // ═══ Performance: جمع المعرفات أولاً لتجنب N+1 ═══
        $productIds = [];
        $variantIds = [];
        foreach ($cart as $key => $entry) {
            if (!is_array($entry)) { $productIds[] = (int) $key; continue; }
            if (!empty($entry['product_id'])) $productIds[] = (int) $entry['product_id'];
            if (!empty($entry['variant_id'])) $variantIds[] = (int) $entry['variant_id'];
        }
        $productIds = array_values(array_unique($productIds));
        $variantIds = array_values(array_unique($variantIds));

        $productsMap = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->where('is_active', true)
            ->whereIn('id', $productIds)->get()->keyBy('id');

        $variantsMap = !empty($variantIds)
            ? \App\Models\ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id')
            : collect();

        foreach ($cart as $key => $entry) {
            if (!is_array($entry)) {
                $entry = ['product_id' => (int) $key, 'variant_id' => null, 'qty' => (int) $entry, 'color' => null, 'size' => null];
            }
            $pid = $entry['product_id'] ?? null;
            if (!$pid) continue;

            $p = $productsMap->get($pid);
            if (!$p) continue;

            $v = !empty($entry['variant_id']) ? $variantsMap->get($entry['variant_id']) : null;
            $qty = max(1, (int) ($entry['qty'] ?? 1));
            $price = ($v && $v->price) ? (float) $v->price : (float) $p->price;

            $items[] = [
                'key' => $key, 'product' => $p, 'variant' => $v,
                'qty' => $qty, 'price' => $price, 'subtotal' => $price * $qty,
                'color' => $entry['color'] ?? ($v->color ?? null),
                'size' => $entry['size'] ?? ($v->size ?? null),
            ];
            $total += $price * $qty;
        }

        $zones = \App\Models\ShippingZone::forShop($shop->id)->active()->ordered()->get();

        return view('storefront.checkout', compact('items', 'total', 'shop', 'zones'));
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:30',
            'customer_address' => 'required|string',
            'zone_id' => 'nullable|integer',
            'zone' => 'nullable|string|max:50',
            'shipping_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cod,wallet,bank',
            'coupon_code' => 'nullable|string|max:50',
            'points_used' => 'nullable|integer|min:0',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return redirect('/cart')->with('error', 'السلة فارغة');
        $shop = $this->shop();
        if (!$shop) return back()->with('error', 'لا يوجد متجر نشط');
        if ((int) session('cart_shop_id') !== (int) $shop->id) {
            return back()->with('error', 'السلة لا تخص المتجر الحالي.');
        }

        $total = 0;
        $itemsData = [];

        // ═══ Performance: جمع المعرفات أولاً لتجنب N+1 ═══
        $productIds = [];
        $variantIds = [];
        foreach ($cart as $key => $entry) {
            if (!is_array($entry)) { $productIds[] = (int) $key; continue; }
            if (!empty($entry['product_id'])) $productIds[] = (int) $entry['product_id'];
            if (!empty($entry['variant_id'])) $variantIds[] = (int) $entry['variant_id'];
        }
        $productIds = array_values(array_unique($productIds));
        $variantIds = array_values(array_unique($variantIds));

        $productsMap = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->where('is_active', true)
            ->whereIn('id', $productIds)->get()->keyBy('id');

        $variantsMap = !empty($variantIds)
            ? \App\Models\ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id')
            : collect();

        foreach ($cart as $key => $entry) {
            // 🧩 Backward compat: دعم البنية القديمة
            if (!is_array($entry)) {
                $entry = ['product_id'=>(int)$key,'variant_id'=>null,'qty'=>(int)$entry,'color'=>null,'size'=>null];
            }
            $pid = $entry['product_id'] ?? null;
            if (!$pid) continue;

            $p = $productsMap->get($pid);
            if (!$p) continue;

            $variant = !empty($entry['variant_id']) ? $variantsMap->get($entry['variant_id']) : null;
            if (!empty($entry['variant_id']) && (!$variant || (int) $variant->product_id !== (int) $p->id || !$variant->is_active)) {
                return back()->with('error', 'أحد المتغيرات في السلة غير صالح.');
            }
            $qty = max(1, (int)($entry['qty'] ?? 1));
            $availableStock = $variant ? (int) $variant->stock : (int) $p->stock;
            if ($availableStock < $qty) {
                return back()->with('error', 'المخزون غير كافٍ للمنتج: ' . $p->name);
            }
            $price = ($variant && $variant->price) ? (float)$variant->price : (float)$p->price;
            $line = $price * $qty;

            $total += $line;

            $itemsData[] = [
                'product_id'   => $p->id,
                'variant_id'   => $variant ? $variant->id : null,
                'color'        => $variant ? $variant->color : ($entry['color'] ?? null),
                'color_hex'    => $variant ? $variant->color_hex : null,
                'size'         => $variant ? $variant->size : ($entry['size'] ?? null),
                'product_name' => $p->name,
                'unit_price'   => $price,
                'quantity'     => $qty,
                'line_total'   => $line,
            ];
        }

        // 🎫 تطبيق الكوبون (بالحقول المتقدمة)
        $discount = 0;
        $couponApplied = null;
        $couponError = null;

        if (!empty($data['coupon_code'])) {
            $coupon = \App\Models\Coupon::withoutGlobalScope('tenant')
                ->where('code', strtoupper(trim($data['coupon_code'])))
                ->where('shop_id', $shop->id)
                ->first();

            if (!$coupon) {
                $couponError = 'الكوبون غير موجود';
            } elseif (!$coupon->isValid()) {
                $couponError = 'الكوبون غير صالح';
            } else {
                // فحص المستخدم (per_user_limit + first_order_only)
                $userCheck = $coupon->canBeUsedBy(auth()->id());
                if (!$userCheck['ok']) {
                    $couponError = $userCheck['message'];
                }
                // فحص الحد الأدنى
                elseif ($total < ($coupon->min_order ?? 0)) {
                    $couponError = 'الحد الأدنى للطلب ' . number_format($coupon->min_order) . ' ر.ي';
                }
                // فحص نطاق التطبيق
                elseif ($coupon->applies_to === 'product' && $coupon->applies_to_id) {
                    $hasProduct = collect($itemsData)->contains('product_id', $coupon->applies_to_id);
                    if (!$hasProduct) {
                        $couponError = 'الكوبون لا ينطبق على منتجات سلتك';
                    }
                } elseif ($coupon->applies_to === 'category' && $coupon->applies_to_id) {
                    $productIds = collect($itemsData)->pluck('product_id');
                    $hasCategory = \App\Models\Product::withoutGlobalScope('tenant')
                        ->whereIn('id', $productIds)
                        ->where('category_id', $coupon->applies_to_id)
                        ->exists();
                    if (!$hasCategory) {
                        $couponError = 'الكوبون لا ينطبق على تصنيفات سلتك';
                    }
                }

                // تطبيق الخصم إذا لا خطأ
                if (!$couponError) {
                    $discount = $coupon->discount($total);
                    if ($discount > 0) {
                        $couponApplied = $coupon->code;
                    }
                }
            }
        }

        // إذا فشل الكوبون → نستمر بدون خصم (لا نوقف الطلب)
        if ($couponError) {
            \Log::info('Coupon failed at placeOrder: ' . $couponError);
        }

        // 🎁 استبدال النقاط (إن وُجدت)
        $pointsUsed = 0;
        $pointsValue = 0;
        $user = auth()->user();

        if ($user && !empty($data['points_used'])) {
            $requestedPoints = (int) $data['points_used'];

            try {
                $loyaltyService = app(\App\Services\Loyalty\LoyaltyService::class);
                $balance = $loyaltyService->getBalance($user->id, $shop->id);
                $availablePoints = (int) ($balance->balance ?? 0);

                // لا تتجاوز المتاح
                $requestedPoints = min($requestedPoints, $availablePoints);

                // لا تتجاوز قيمة الطلب المتبقية
                $maxValue = max(0, $total - $discount);
                $maxPoints = (int) floor($maxValue / 10) * 100; // كل 100 نقطة = 10 ر.ي
                $requestedPoints = min($requestedPoints, $maxPoints);

                // يجب أن تكون من مضاعفات 100
                $requestedPoints = (int) floor($requestedPoints / 100) * 100;

                if ($requestedPoints > 0) {
                    $result = $loyaltyService->redeem($user->id, $requestedPoints, $shop->id);
                    if (!empty($result['ok'])) {
                        $pointsUsed = $requestedPoints;
                        $pointsValue = (float) $result['value'];
                    }
                }
            } catch (\Throwable $e) {
                \Log::warning('Points redemption failed: ' . $e->getMessage());
            }
        }

        $shipping = 0.0;
        if (!empty($data['zone_id'])) {
            $zone = \App\Models\ShippingZone::forShop($shop->id)->active()->find($data['zone_id']);
            if (!$zone) return back()->with('error', 'منطقة الشحن غير صالحة.');
            $shippingResult = $zone->calculateFeeWithStatus($total - $discount);
            if (!empty($shippingResult['requires_quote'])) return back()->with('error', 'هذا الطلب يحتاج تسعير شحن يدوي.');
            $shipping = (float) ($shippingResult['fee'] ?? 0);
        }
        $finalTotal = max(0, $total + $shipping - $discount - $pointsValue);

        $order = DB::transaction(function () use ($shop, $itemsData, $total, $shipping, $finalTotal, $data, $couponApplied, $discount, $pointsUsed, $pointsValue) {
            $order = Order::withoutGlobalScope('tenant')->create([
            'shop_id' => $shop->id,
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'user_id' => auth()->id(),
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_address' => $data['customer_address'],
            'notes' => $data['notes'] ?? null,
            'subtotal' => $total,
            'shipping' => $shipping,
            'total' => $finalTotal,
            'currency' => $shop->currency ?? 'YER',
            'payment_method' => $data['payment_method'],
            'payment_status' => 'pending',
            'status' => 'awaiting_payment',
            'coupon_code' => $couponApplied,
            'discount' => $discount,
            'points_used' => $pointsUsed,
            'points_value' => $pointsValue,
        ]);

            foreach ($itemsData as $item) {
                if ($item['variant_id']) {
                    $updated = \App\Models\ProductVariant::whereKey($item['variant_id'])
                        ->where('product_id', $item['product_id'])
                        ->where('is_active', true)
                        ->where('stock', '>=', $item['quantity'])
                        ->decrement('stock', $item['quantity']);
                } else {
                    $updated = Product::withoutGlobalScope('tenant')
                        ->whereKey($item['product_id'])
                        ->where('shop_id', $shop->id)
                        ->where('stock', '>=', $item['quantity'])
                        ->decrement('stock', $item['quantity']);
                }
                if (!$updated) throw new \RuntimeException('Stock changed during checkout.');
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }
            return $order;
        });

        if ($couponApplied) {
            \App\Models\Coupon::withoutGlobalScope('tenant')
                ->where('shop_id', $shop->id)
                ->where('code', $couponApplied)
                ->increment('used_count');
        }

        // 🎁 منح نقاط الولاء للعميل المسجّل
        $userId = $order->user_id ?? ($request->user()?->id);
        if ($userId) {
            try {
                app(\App\Services\Loyalty\LoyaltyService::class)
                    ->award($userId, $finalTotal, $shop->id, 'نقاط من الطلب ' . $order->order_number, $order->id);
            } catch (\Throwable $e) {
                \Log::warning('Loyalty award failed: ' . $e->getMessage());
            }
        }

        // 📱 إشعارات الطلب
        try {
            app(\App\Services\Notifications\NotificationService::class)
                ->orderPlaced($order, $shop);
        } catch (\Throwable $e) {
            \Log::warning('Order notification failed: ' . $e->getMessage());
        }

        session()->forget(['cart', 'cart_shop_id']);
        session(['last_order_id' => $order->id]);
        return redirect('/order-success/' . $order->id);
    }

    public function orderSuccess($id)
    {
        $shop = $this->shop();
        if (!$shop) abort(404);
        $order = Order::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->whereKey($id)
            ->where(function ($q) {
                $q->where('id', session('last_order_id'));
                if (auth()->check()) {
                    $q->orWhere('user_id', auth()->id());
                }
            })
            ->with('items')->firstOrFail();
        return view('storefront.success', compact('order', 'shop'));
    }

    public function trackOrder(Request $request)
    {
        $number = trim((string) $request->input('order_number'));
        $shop = $this->shop();
        
        $query = Order::withoutGlobalScope('tenant')
            ->where('order_number', $number)
            ->with('items');
        
        // 🔒 تقييد بالمتجر الحالي لمنع تسريب الطلبات بين المتاجر
        if ($shop) {
            $query->where('shop_id', $shop->id);
        }
        
        $order = $query->first();
        
        return view('storefront.track', compact('order', 'number', 'shop'));
    }

    /**
     * 🔍 بحث Autocomplete — اقتراحات فورية
     */
    public function autocomplete(\Illuminate\Http\Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['ok' => true, 'results' => []]);
        }

        $shop = $this->shop();
        $shopId = $shop ? $shop->id : null;

        $query = \App\Models\Product::withoutGlobalScope('tenant')
            ->where('is_active', true)
            ->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });

        if ($shopId) {
            $query->where('shop_id', $shopId);
        }

        $products = $query->orderByDesc('sold_count')
            ->orderBy('name')
            ->limit(8)
            ->get(['id', 'name', 'price', 'compare_price', 'image', 'stock', 'category_id']);

        $results = $products->map(function ($p) use ($q) {
            $img = $p->image;
            $isExt = $img && (str_starts_with($img, 'http') || str_starts_with($img, 'https'));
            $imgUrl = $img ? ($isExt ? $img : \Storage::url($img)) : null;

            // حساب الخصم
            $discount = ($p->compare_price && $p->compare_price > $p->price)
                ? round((1 - ($p->price / $p->compare_price)) * 100) : 0;

            // تمييز النص المطابق (اختياري)
            $highlighted = preg_replace(
                '/(' . preg_quote($q, '/') . ')/iu',
                '<mark>$1</mark>',
                e($p->name)
            );

            return [
                'id' => $p->id,
                'name' => $p->name,
                'name_highlighted' => $highlighted,
                'price' => (float) $p->price,
                'price_formatted' => number_format($p->price),
                'compare_price' => $p->compare_price ? (float) $p->compare_price : null,
                'compare_formatted' => $p->compare_price ? number_format($p->compare_price) : null,
                'discount' => $discount,
                'image' => $imgUrl,
                'stock' => (int) $p->stock,
                'url' => '/product/' . $p->id,
            ];
        });

        // التصنيفات المطابقة
        $categories = collect();
        if ($shopId) {
            $categories = \App\Models\Category::withoutGlobalScope('tenant')
                ->where('shop_id', $shopId)
                ->where('is_active', true)
                ->where('name', 'like', "%{$q}%")
                ->limit(3)
                ->get(['id', 'name'])
                ->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'url' => '/shop?category=' . $cat->id,
                    ];
                });
        }

        return response()->json([
            'ok' => true,
            'query' => $q,
            'count' => $results->count() + $categories->count(),
            'products' => $results,
            'categories' => $categories,
        ]);
    }

}

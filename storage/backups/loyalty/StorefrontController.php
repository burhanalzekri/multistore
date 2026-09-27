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

class StorefrontController extends Controller
{
    private function shop()
    {
        return app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
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
        $product = Product::withoutGlobalScope('tenant')->findOrFail($id);
        $shop = Shop::find($product->shop_id);

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

        return view('storefront.product', compact('product', 'shop', 'reviews', 'related', 'inWishlist', 'ratingStats'));
    }

    public function cart()
    {
        $cart = session('cart', []);
        $items = [];
        $total = 0;
        foreach ($cart as $productId => $qty) {
            $p = Product::withoutGlobalScope('tenant')->find($productId);
            if ($p) {
                $items[] = ['product' => $p, 'qty' => $qty, 'subtotal' => $p->price * $qty];
                $total += $p->price * $qty;
            }
        }
        return view('storefront.cart', compact('items', 'total'));
    }

    public function addToCart(Request $request, $id)
    {
        Product::withoutGlobalScope('tenant')->findOrFail($id);
        $cart = session('cart', []);
        $cart[$id] = ($cart[$id] ?? 0) + 1;
        session(['cart' => $cart]);

        // إذا كان AJAX — أعد JSON
        if ($request->expectsJson() || $request->ajax()) {
            $count = array_sum($cart);
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
        if ($qty <= 0) unset($cart[$id]); else $cart[$id] = $qty;
        session(['cart' => $cart]);
        return redirect('/cart');
    }

    public function removeFromCart($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return redirect('/cart')->with('success', 'تم حذف المنتج');
    }

    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect('/cart')->with('error', 'السلة فارغة');
        $shop = $this->shop();
        $items = [];
        $total = 0;
        foreach ($cart as $productId => $qty) {
            $p = Product::withoutGlobalScope('tenant')->find($productId);
            if ($p) {
                $items[] = ['product' => $p, 'qty' => $qty, 'subtotal' => $p->price * $qty];
                $total += $p->price * $qty;
            }
        }
        return view('storefront.checkout', compact('items', 'total', 'shop'));
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:30',
            'customer_address' => 'required|string',
            'zone' => 'nullable|string|max:50',
            'shipping_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cod,wallet,bank',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return redirect('/cart')->with('error', 'السلة فارغة');
        $shop = $this->shop();
        if (!$shop) return back()->with('error', 'لا يوجد متجر نشط');

        $total = 0;
        $itemsData = [];
        foreach ($cart as $productId => $qty) {
            $p = Product::withoutGlobalScope('tenant')->find($productId);
            if ($p) {
                $line = $p->price * $qty;
                $total += $line;
                $itemsData[] = [
                    'product_id' => $p->id,
                    'product_name' => $p->name,
                    'unit_price' => $p->price,
                    'quantity' => $qty,
                    'line_total' => $line,
                ];
            }
        }

        // 🎫 تطبيق الكوبون
        $discount = 0;
        if (!empty($data['coupon_code'])) {
            $coupon = \App\Models\Coupon::withoutGlobalScope('tenant')
                ->where('code', strtoupper(trim($data['coupon_code'])))
                ->where('is_active', true)
                ->first();

            if ($coupon && $coupon->isValid() && $total >= $coupon->min_order) {
                $discount = $coupon->discount($total);
                $coupon->increment('used_count');
            }
        }

        $shipping = (float) ($data['shipping_fee'] ?? 0);
        $finalTotal = max(0, $total + $shipping - $discount);

        $order = Order::withoutGlobalScope('tenant')->create([
            'shop_id' => $shop->id,
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
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
        ]);

        foreach ($itemsData as $item) {
            OrderItem::create(array_merge($item, ['order_id' => $order->id]));
        }

        session()->forget('cart');
        return redirect('/order-success/' . $order->id);
    }

    public function orderSuccess($id)
    {
        $order = Order::withoutGlobalScope('tenant')->with('items')->findOrFail($id);
        $shop = Shop::find($order->shop_id);
        return view('storefront.success', compact('order', 'shop'));
    }

    public function trackOrder(Request $request)
    {
        $number = $request->input('order_number');
        $order = Order::withoutGlobalScope('tenant')->where('order_number', $number)->with('items')->first();
        return view('storefront.track', compact('order', 'number'));
    }
}
